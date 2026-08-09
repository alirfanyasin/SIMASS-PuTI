<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Overtime;
use App\Models\OvertimeTransfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OvertimeController extends Controller
{
    public function index(Request $request): View
    {
        $now = Carbon::now();

        [$month, $year] = $this->resolveMonthYear($request, $now);

        [$startDate, $endDate] = Holiday::getEffectiveCycleDates($month, $year);

        // Allow manual date override
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
        }
        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        }

        $overtimeQuery = Overtime::with('user')
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('tanggal', 'desc');

        $transferQuery = OvertimeTransfer::with(['user', 'presence'])
            ->whereBetween('tanggal_transfer', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('tanggal_transfer', 'desc');

        if (! Auth::user()->can('manage-presence')) {
            $overtimeQuery->where('user_id', Auth::id());
            $transferQuery->where('user_id', Auth::id());
        } else {
            if ($request->filled('user_id') && $request->user_id !== 'all') {
                $overtimeQuery->where('user_id', $request->user_id);
                $transferQuery->where('user_id', $request->user_id);
            }
        }

        $allOvertimes = $overtimeQuery->get();
        $allTransfers = $transferQuery->get();

        $totalAkumulasiMenit = $allOvertimes->sum('durasi_menit');
        $totalSaldoMenit = $allOvertimes->sum('sisa_menit');
        $totalAlokasiMenit = $allTransfers->sum('durasi_menit');

        $saldoLembur = $allOvertimes->map(fn ($o) => $this->formatOvertimeRow($o))->toArray();
        $riwayatAlokasi = $allTransfers->map(fn ($t) => $this->formatTransferRow($t))->toArray();

        $statusMap = $this->getStatusMap();
        $users = User::whereNotNull('position')->get();
        $monthNames = $this->getMonthNames();
        $periods = $this->buildPeriodOptions($now, $monthNames);
        $currentPeriodLabel = $monthNames[$month].' '.$year;

        $prevDate = Carbon::createFromDate($year, $month, 1)->subMonth();
        $nextDate = Carbon::createFromDate($year, $month, 1)->addMonth();

        // Fetch eligible presences (less than 8 hours) grouped by user_id
        $eligiblePresences = [];
        $presencesQuery = \App\Models\Presence::whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->whereNotNull('jam_pulang');
            
        if (! Auth::user()->can('manage-presence')) {
            $presencesQuery->where('user_id', Auth::id());
        }
            
        $presences = $presencesQuery->get();
        foreach ($presences as $p) {
            $jamMasuk = Carbon::parse($p->tanggal.' '.$p->jam_masuk);
            $jamPulang = Carbon::parse($p->tanggal.' '.$p->jam_pulang);
            $totalMenit = $jamMasuk->diffInMinutes($jamPulang);
            if ($totalMenit < 480) { // less than 8 hours
                $kurangMenit = 480 - $totalMenit;
                $eligiblePresences[$p->user_id][] = [
                    'id' => $p->id,
                    'tanggal' => Carbon::parse($p->tanggal)->translatedFormat('l, d M Y'),
                    'durasi' => $this->formatMinutes($totalMenit),
                    'kurang_menit' => $kurangMenit,
                    'kurang_format' => $this->formatMinutes($kurangMenit),
                ];
            }
        }

        return view('pages.presence.overtime', compact(
            'totalAkumulasiMenit',
            'totalSaldoMenit',
            'totalAlokasiMenit',
            'saldoLembur',
            'riwayatAlokasi',
            'statusMap',
            'users',
            'startDate',
            'endDate',
            'month',
            'year',
            'periods',
            'currentPeriodLabel',
            'prevDate',
            'nextDate',
            'monthNames',
            'eligiblePresences'
        ));
    }

    public function transfer(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'overtime_id' => ['required', 'exists:overtimes,id'],
            'is_wfh' => ['nullable', 'boolean'],
            'presence_id' => ['required_without:is_wfh', 'nullable', 'exists:presences,id'],
            'tanggal_wfh' => ['required_if:is_wfh,1', 'nullable', 'date'],
            'keterangan_wfh' => ['required_if:is_wfh,1', 'nullable', 'string', 'max:500'],
            'durasi_menit' => ['required', 'integer', 'min:1'],
        ]);

        $overtime = Overtime::findOrFail($data['overtime_id']);

        if ($data['durasi_menit'] > $overtime->sisa_menit) {
            return back()->withErrors(['durasi_menit' => 'Durasi transfer melebihi saldo lembur tersedia.']);
        }

        $presenceId = $data['presence_id'] ?? null;

        // If WFH, create a Presence record to allocate to
        if (!empty($data['is_wfh']) && $data['is_wfh']) {
            $presence = \App\Models\Presence::create([
                'user_id' => $overtime->user_id,
                'tanggal' => $data['tanggal_wfh'],
                'jam_masuk' => '08:00:00',
                'jam_pulang' => '16:00:00', // Assumed 8 hours for WFH full day? No, wait. 
                'hari' => match (Carbon::parse($data['tanggal_wfh'])->dayOfWeekIso) {
                    1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu', default => '-'
                },
                'pekerjaan' => $data['keterangan_wfh'],
                'menit_tambahan' => 0,
                'total_jam' => '0 jam 0 menit', // Handled by transfer
            ]);
            $presenceId = $presence->id;
        }

        OvertimeTransfer::create([
            'user_id' => $overtime->user_id,
            'overtime_id' => $overtime->id,
            'presence_id' => $presenceId,
            'tanggal_transfer' => Carbon::today()->format('Y-m-d'),
            'durasi_menit' => $data['durasi_menit'],
            'keterangan' => $data['keterangan_wfh'] ?? null,
        ]);

        // Deduct from overtime balance
        $overtime->decrement('sisa_menit', $data['durasi_menit']);

        return back()->with('status', 'Transfer lembur berhasil dilakukan.');
    }

    // -------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------

    /** @return array{int, int} */
    private function resolveMonthYear(Request $request, Carbon $now): array
    {
        if ($request->has('month') && $request->has('year')) {
            return [(int) $request->input('month'), (int) $request->input('year')];
        }

        if ($now->day >= 16) {
            $next = $now->copy()->addMonth();

            return [$next->month, $next->year];
        }

        return [$now->month, $now->year];
    }

    /** @return array<string, mixed> */
    private function formatOvertimeRow(Overtime $o): array
    {
        $status = match (true) {
            $o->sisa_menit <= 0 => 'habis',
            $o->sisa_menit === $o->durasi_menit => 'penuh',
            default => 'sebagian',
        };

        return [
            'id' => $o->id,
            'user_id' => $o->user_id,
            'nama' => $o->user?->name ?? 'Unknown',
            'tgl' => Carbon::parse($o->tanggal)->translatedFormat('d M Y'),
            'durasi' => $this->formatMinutes($o->durasi_menit),
            'saldo' => $this->formatMinutes($o->sisa_menit),
            'raw_saldo' => $o->sisa_menit,
            'status' => $status,
        ];
    }

    /** @return array<string, mixed> */
    private function formatTransferRow(OvertimeTransfer $t): array
    {
        $target = $t->presence
            ? Carbon::parse($t->presence->tanggal)->translatedFormat('d M Y')
            : 'Unknown';

        return [
            'nama' => $t->user?->name ?? 'Unknown',
            'tgl' => Carbon::parse($t->tanggal_transfer)->translatedFormat('d M Y'),
            'target' => $target,
            'jumlah' => $this->formatMinutes($t->durasi_menit),
        ];
    }

    private function formatMinutes(int $menit): string
    {
        $jam = floor($menit / 60);
        $sisa = $menit % 60;

        return "{$jam} Jam {$sisa} Menit";
    }

    /** @return array<string, array<string, string>> */
    private function getStatusMap(): array
    {
        return [
            'penuh' => ['label' => 'Tersedia Penuh', 'cls' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            'sebagian' => ['label' => 'Terpakai Sebagian', 'cls' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
            'habis' => ['label' => 'Habis Terpakai', 'cls' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'],
        ];
    }

    /** @return array<int, string> */
    private function getMonthNames(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    /**
     * Build a list of the last 12 period options.
     *
     * @param  array<int, string>  $monthNames
     * @return array<int, array<string, mixed>>
     */
    private function buildPeriodOptions(Carbon $now, array $monthNames): array
    {
        $periods = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $periods[] = [
                'month' => $d->month,
                'year' => $d->year,
                'label' => $monthNames[$d->month].' '.$d->year,
            ];
        }

        return $periods;
    }
}
