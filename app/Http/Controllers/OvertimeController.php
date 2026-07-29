<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Overtime;
use App\Models\OvertimeTransfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        // Determine the active cycle period:
        // Cycle name = the month that contains the 15th end date.
        // If today >= 16 → cycle started this month → endpoint is NEXT month's 15th.
        // If today <= 15 → we're still in the current month's cycle.
        if ($request->has('month') && $request->has('year')) {
            $month = (int) $request->input('month');
            $year = (int) $request->input('year');
        } elseif ($now->day >= 16) {
            $next = $now->copy()->addMonth();
            $month = $next->month;
            $year = $next->year;
        } else {
            $month = $now->month;
            $year = $now->year;
        }

        // Get effective cycle dates (same logic as presence-list: 16th prev month to 15th this month)
        [$startDate, $endDate] = Holiday::getEffectiveCycleDates($month, $year);

        // Allow manual override via request (same as presence-list)
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
        }
        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        }

        $overtimeQuery = Overtime::with('user')->orderBy('tanggal', 'desc');
        $transferQuery = OvertimeTransfer::with(['user', 'presence'])->orderBy('tanggal_transfer', 'desc');

        // Filter by period
        $overtimeQuery->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        $transferQuery->whereBetween('tanggal_transfer', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        // Filter by user
        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $overtimeQuery->where('user_id', $request->user_id);
            $transferQuery->where('user_id', $request->user_id);
        }

        $allOvertimes = $overtimeQuery->get();
        $allTransfers = $transferQuery->get();

        $totalAkumulasiMenit = $allOvertimes->sum('durasi_menit');
        $totalSaldoMenit = $allOvertimes->sum('sisa_menit');
        $totalAlokasiMenit = $allTransfers->sum('durasi_menit');

        $saldoLembur = $allOvertimes->map(function ($o) {
            if ($o->sisa_menit == $o->durasi_menit) {
                $status = 'penuh';
            } elseif ($o->sisa_menit <= 0) {
                $status = 'habis';
            } else {
                $status = 'sebagian';
            }

            $jamDurasi = floor($o->durasi_menit / 60);
            $menitDurasi = $o->durasi_menit % 60;
            $jamSaldo = floor($o->sisa_menit / 60);
            $menitSaldo = $o->sisa_menit % 60;

            return [
                'nama' => $o->user ? $o->user->name : 'Unknown',
                'tgl' => date('d M Y', strtotime($o->tanggal)),
                'durasi' => "{$jamDurasi} Jam {$menitDurasi} Menit",
                'saldo' => "{$jamSaldo} Jam {$menitSaldo} Menit",
                'status' => $status,
            ];
        })->toArray();

        $riwayatAlokasi = $allTransfers->map(function ($t) {
            $jam = floor($t->durasi_menit / 60);
            $menit = $t->durasi_menit % 60;

            $target = 'Unknown';
            if ($t->presence) {
                $target = date('d M Y', strtotime($t->presence->tanggal));
            }

            return [
                'nama' => $t->user ? $t->user->name : 'Unknown',
                'tgl' => date('d M Y', strtotime($t->tanggal_transfer)),
                'target' => $target,
                'jumlah' => "{$jam} Jam {$menit} Menit",
            ];
        })->toArray();

        $statusMap = [
            'penuh' => ['label' => 'Tersedia Penuh', 'cls' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            'sebagian' => ['label' => 'Terpakai Sebagian', 'cls' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
            'habis' => ['label' => 'Habis Terpakai', 'cls' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'],
        ];

        $users = User::whereNotNull('position')->get();

        // Build list of period options (last 12 months)
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $periods = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $periods[] = [
                'month' => $d->month,
                'year' => $d->year,
                'label' => $monthNames[$d->month].' '.$d->year,
            ];
        }

        $currentPeriodLabel = $monthNames[$month].' '.$year;
        $prevDate = Carbon::createFromDate($year, $month, 1)->subMonth();
        $nextDate = Carbon::createFromDate($year, $month, 1)->addMonth();

        return view('pages.app.overtime', compact(
            'totalAkumulasiMenit', 'totalSaldoMenit', 'totalAlokasiMenit',
            'saldoLembur', 'riwayatAlokasi', 'statusMap', 'users',
            'startDate', 'endDate', 'month', 'year',
            'periods', 'currentPeriodLabel', 'prevDate', 'nextDate', 'monthNames'
        ));
    }
}
