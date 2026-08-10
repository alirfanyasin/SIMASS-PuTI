<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Overtime;
use App\Models\OvertimeTransfer;
use App\Models\Presence;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PresenceController extends Controller
{
    public function presence(): View
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $dayName = $this->getDayName(Carbon::today()->dayOfWeekIso);

        $todayPresence = Presence::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        $isHoliday = Holiday::isOff($today);
        $faceDescriptor = $user->face_descriptor;

        $officeLat = Setting::where('key', 'office_latitude')->value('value');
        $officeLng = Setting::where('key', 'office_longitude')->value('value');
        $officeRadius = Setting::where('key', 'office_radius')->value('value') ?: 100;

        return view('pages.presence.presence', compact('todayPresence', 'isHoliday', 'dayName', 'today', 'faceDescriptor', 'officeLat', 'officeLng', 'officeRadius'));
    }

    public function registerFace(Request $request): JsonResponse
    {
        $request->validate([
            'face_descriptor' => ['required', 'string'],
        ]);

        $user = Auth::user();
        // Skip validation because we directly update
        User::where('id', $user->id)->update([
            'face_descriptor' => $request->input('face_descriptor'),
        ]);

        return response()->json(['message' => 'Wajah berhasil didaftarkan.']);
    }

    public function removeFace(): RedirectResponse
    {
        $user = Auth::user();
        User::where('id', $user->id)->update([
            'face_descriptor' => null,
        ]);

        return back()->with('status', 'Data wajah berhasil dihapus. Anda tidak dapat melakukan presensi wajah sampai Anda mendaftar ulang.');
    }

    public function checkIn(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        // Geolocation Check for student-staff
        if ($user->hasRole('student-staff')) {
            $lat = $request->input('latitude');
            $lng = $request->input('longitude');

            if (! $lat || ! $lng) {
                return back()->withErrors(['location' => 'Lokasi Anda tidak terdeteksi. Pastikan Anda memberikan izin akses lokasi pada browser.']);
            }

            $officeLat = Setting::where('key', 'office_latitude')->value('value');
            $officeLng = Setting::where('key', 'office_longitude')->value('value');
            $officeRadius = Setting::where('key', 'office_radius')->value('value') ?: 100;

            if ($officeLat && $officeLng) {
                $distance = $this->calculateDistance($officeLat, $officeLng, $lat, $lng);
                if ($distance > $officeRadius) {
                    return back()->withErrors(['location' => 'Anda berada di luar jangkauan presensi ('.round($distance).' meter dari batas yang diizinkan).']);
                }
            }
        }

        // Prevent duplicate check-in
        if (Presence::where('user_id', $user->id)->where('tanggal', $today)->exists()) {
            return back()->withErrors(['check_in' => 'Anda sudah melakukan check-in hari ini.']);
        }

        // $request->validate([
        //     'pekerjaan' => ['required', 'string', 'max:500'],
        // ]);

        Presence::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => $now->format('H:i:s'),
            'hari' => $this->getDayName($now->dayOfWeekIso),
            'pekerjaan' => null,
            'menit_tambahan' => 0,
        ]);

        return redirect()->route('presence.list')->with('status', 'Check-in berhasil! Selamat bekerja 🎉');
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        // Geolocation Check for student-staff
        if ($user->hasRole('student-staff')) {
            $lat = $request->input('latitude');
            $lng = $request->input('longitude');

            if (! $lat || ! $lng) {
                return back()->withErrors(['location' => 'Lokasi Anda tidak terdeteksi. Pastikan Anda memberikan izin akses lokasi pada browser.']);
            }

            $officeLat = Setting::where('key', 'office_latitude')->value('value');
            $officeLng = Setting::where('key', 'office_longitude')->value('value');
            $officeRadius = Setting::where('key', 'office_radius')->value('value') ?: 100;

            if ($officeLat && $officeLng) {
                $distance = $this->calculateDistance($officeLat, $officeLng, $lat, $lng);
                if ($distance > $officeRadius) {
                    return back()->withErrors(['location' => 'Anda berada di luar jangkauan presensi ('.round($distance).' meter dari batas yang diizinkan).']);
                }
            }
        }

        $presence = Presence::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->whereNull('jam_pulang')
            ->firstOrFail();

        $request->validate([
            'pekerjaan' => ['required', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $jamMasuk = Carbon::parse($presence->tanggal.' '.$presence->jam_masuk);
        $totalDetik = $jamMasuk->diffInSeconds($now);
        $jam = floor($totalDetik / 3600);
        $menit = floor(($totalDetik % 3600) / 60);

        // Calculate overtime — standard working hours = 8 hours (28800 seconds)
        $standardDetik = 28800;
        $overtimeDetik = max(0, $totalDetik - $standardDetik);
        $overtimeMenit = (int) round($overtimeDetik / 60);

        $fotoPath = $presence->foto;
        if ($request->filled('foto_base64')) {
            $base64 = $request->input('foto_base64');
            $imageParts = explode(';base64,', $base64);
            $imageBase64 = base64_decode($imageParts[1]);
            $fileName = uniqid().'.png';
            $path = 'presence/'.$fileName;
            Storage::disk('public')->put($path, $imageBase64);
            $fotoPath = $path;
        } elseif ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('presence', 'public');
        }

        $presence->update([
            'jam_pulang' => $now->format('H:i:s'),
            'pekerjaan' => $request->string('pekerjaan'),
            'foto' => $fotoPath,
            'total_jam' => "{$jam}j {$menit}m",
            'menit_tambahan' => $overtimeMenit,
        ]);

        // Auto-create overtime record if applicable
        if ($overtimeMenit > 0) {
            Overtime::create([
                'user_id' => $user->id,
                'presence_id' => $presence->id,
                'tanggal' => $today,
                'durasi_menit' => $overtimeMenit,
                'sisa_menit' => $overtimeMenit,
                'keterangan' => 'Lembur otomatis (kelebihan jam kerja harian)',
            ]);
        }

        return redirect()->route('presence.list')->with('status', "Check-out berhasil! Total kerja: {$jam}j {$menit}m 👋");
    }

    public function presenceList(Request $request): View
    {
        $query = Presence::with('user')->orderBy('tanggal', 'desc');

        if (Auth::user()->hasRole('student-staff') || Auth::user()->can('manage-presence')) {
            $query->whereHas('user', function ($q) {
                $q->role('student-staff');
            });
        } else {
            $query->where('user_id', Auth::user()->id);
        }

        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        [$startDate, $endDate] = $this->resolveActiveCycleDates($request);

        $query->whereBetween('tanggal', [$startDate, $endDate]);

        $presences = $query->get();

        $presensiData = $presences->map(fn ($p) => $this->formatPresenceRow($p))->toArray();

        $statusMap = $this->getStatusMap();
        $users = User::role('student-staff')->get();

        return view('pages.presence.presence-list', compact('presensiData', 'statusMap', 'users', 'startDate', 'endDate'));
    }

    public function presenceHistory(Request $request): View
    {
        $query = Presence::with('user')->orderBy('tanggal', 'desc');

        if (Auth::user()->hasRole('student-staff') || Auth::user()->can('manage-presence') || Auth::user()->hasRole('super-admin')) {
            if ($request->filled('user_id') && $request->user_id !== 'all') {
                $query->where('user_id', $request->user_id);
            }
        } else {
            $query->where('user_id', Auth::id());
        }

        $allPresences = $query->get();

        $grouped = [];

        foreach ($allPresences as $p) {
            $date = Carbon::parse($p->tanggal);
            $day = $date->day;
            $month = $date->month;
            $year = $date->year;

            if ($day > 15) {
                $payMonth = $month === 12 ? 1 : $month + 1;
                $payYear = $month === 12 ? $year + 1 : $year;
            } else {
                $payMonth = $month;
                $payYear = $year;
            }

            $periodKey = sprintf('%04d-%02d', $payYear, $payMonth);

            if (! isset($grouped[$periodKey])) {
                [$effStart, $effEnd] = Holiday::getEffectiveCycleDates($payMonth, $payYear);

                $grouped[$periodKey] = [
                    'id' => $periodKey,
                    'title' => $this->getMonthName($payMonth).' '.$payYear,
                    'period' => $this->formatPeriodLabel($effStart, $effEnd),
                    'effStart' => $effStart->format('Y-m-d'),
                    'effEnd' => $effEnd->format('Y-m-d'),
                    'total' => 0,
                    'staffs' => [],
                    'detailData' => [],
                ];
            }

            if ($p->tanggal >= $grouped[$periodKey]['effStart'] && $p->tanggal <= $grouped[$periodKey]['effEnd']) {
                $actual = 0;
                if ($p->jam_masuk && $p->jam_pulang) {
                    $actual = Carbon::parse($p->tanggal.' '.$p->jam_masuk)->diffInMinutes(Carbon::parse($p->tanggal.' '.$p->jam_pulang));
                }
                $transferred = OvertimeTransfer::where('presence_id', $p->id)->sum('durasi_menit');
                $total = $actual + $transferred;

                $waktu = ($p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-').' - '.($p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-');
                if ($actual === 0 && $total > 0 && $p->jam_masuk) {
                    $start = Carbon::parse($p->tanggal.' '.$p->jam_masuk);
                    $end = $start->copy()->addMinutes($total);
                    $waktu = $start->format('H:i').' - '.$end->format('H:i');
                }

                $grouped[$periodKey]['total']++;
                $grouped[$periodKey]['staffs'][$p->user_id] = true;
                $grouped[$periodKey]['detailData'][] = [
                    'id' => $p->id,
                    'nama' => $p->user?->name ?? 'Unknown',
                    'hari' => $p->hari ?? '-',
                    'tgl' => Carbon::parse($p->tanggal)->translatedFormat('d M Y'),
                    'waktu' => $waktu,
                    'jam' => $p->total_jam ?? '-',
                    'pekerjaan' => $p->pekerjaan ?? 'Tidak ada deskripsi',
                    'foto' => $p->foto ? url('storage/'.$p->foto) : null,
                ];
            }
        }

        krsort($grouped);

        $months = array_map(function ($g) {
            $g['staff'] = count($g['staffs']).' Orang';

            return $g;
        }, $grouped);

        $selectedPeriod = $request->query('period');
        $detailData = [];
        $detailTitle = '';
        $detailPeriod = '';
        $calendarDays = [];
        $viewMode = 'list';

        $startDate = null;
        $endDate = null;

        if ($selectedPeriod && isset($grouped[$selectedPeriod])) {
            $g = $grouped[$selectedPeriod];
            $detailData = $g['detailData'];
            $detailTitle = $g['title'];
            $detailPeriod = $g['period'];
            $viewMode = 'detail';
            $startDate = $g['effStart'];
            $endDate = $g['effEnd'];

            $start = Carbon::parse($g['effStart']);
            $end = Carbon::parse($g['effEnd']);

            while ($start <= $end) {
                $calendarDays[] = [
                    'day' => $start->format('j'),
                    'is_weekend' => $start->isWeekend(),
                    'is_holiday' => Holiday::isOff($start->format('Y-m-d')),
                ];
                $start->addDay();
            }
        }

        $users = User::role('student-staff')->get();

        return view('pages.presence.presence-history', compact(
            'months', 'detailData', 'detailTitle', 'detailPeriod', 'viewMode', 'calendarDays', 'startDate', 'endDate', 'users'
        ));
    }

    public function update(Request $request, Presence $presence): RedirectResponse
    {
        if (Auth::id() !== $presence->user_id && ! Auth::user()->can('manage-presence')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'jam_masuk' => ['required'],
            'jam_pulang' => ['nullable'],
            'pekerjaan' => ['nullable', 'string'],
        ]);

        $presence->update($data);
        $presence->recalculateTotalJam();

        return back()->with('status', 'Data presensi berhasil diperbarui.');
    }

    public function destroy(Presence $presence): RedirectResponse
    {
        if (Auth::id() !== $presence->user_id && ! Auth::user()->can('manage-presence')) {
            abort(403, 'Unauthorized action.');
        }

        $presence->delete();

        return back()->with('status', 'Data presensi berhasil dihapus.');
    }

    public function exportPdf(Request $request): View
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');
        $filterNama = $request->query('filterNama');

        $query = Presence::with('user')->orderBy('tanggal', 'asc');

        if ($filterNama && $filterNama !== 'all') {
            $query->where('user_id', $filterNama);
            $user = User::find($filterNama);
            $nama = $user ? $user->name : 'Semua';
        } else {
            $nama = 'Semua Student Staff';
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $presences = $query->get();
        $formattedData = [];
        $totalJam = 0;

        foreach ($presences as $p) {
            $jam = 0;
            $stdMasuk = '-';
            $stdPulang = '-';

            if ($p->jam_masuk && $p->jam_pulang) {
                $masuk = Carbon::parse($p->tanggal.' '.$p->jam_masuk);
                $pulang = Carbon::parse($p->tanggal.' '.$p->jam_pulang);

                $actual = $masuk->diffInMinutes($pulang);
                $transferred = OvertimeTransfer::where('presence_id', $p->id)->sum('durasi_menit');
                $diffInMinutes = $actual + $transferred;
                $hours = floor($diffInMinutes / 60);
                $remainder = $diffInMinutes % 60;

                if ($remainder > 30) {
                    $hours += 1;
                }

                if ($hours > 8) {
                    $hours = 8;
                }

                $jam = $hours;

                if ($jam > 0) {
                    $stdMasuk = '08:30';
                    $endHour = 8 + $jam;
                    $stdPulang = sprintf('%02d:30', $endHour);
                }

                $totalJam += $jam;
            }

            $formattedData[] = (object) [
                'tanggal' => Carbon::parse($p->tanggal)->locale('id')->translatedFormat('l, d F Y'),
                'waktu' => $stdMasuk !== '-' ? $stdMasuk.' s/d '.$stdPulang : '-',
                'durasi' => $jam > 0 ? $jam.' Jam' : '-',
                'pekerjaan' => $p->pekerjaan ?? '-',
            ];
        }

        $formattedStartDate = $startDate ? Carbon::parse($startDate)->locale('id')->translatedFormat('j F Y') : '-';
        $formattedEndDate = $endDate ? Carbon::parse($endDate)->locale('id')->translatedFormat('j F Y') : '-';
        $bulanHeader = $startDate ? Carbon::parse($startDate)->locale('id')->translatedFormat('F Y') : '';

        return view('pages.presence.export-pdf', compact('formattedData', 'nama', 'formattedStartDate', 'formattedEndDate', 'totalJam', 'bulanHeader'));
    }

    // -------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function resolveActiveCycleDates(Request $request): array
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (! $startDate || ! $endDate) {
            $now = Carbon::now();
            $cycleDate = $now->day >= 16 ? $now->copy()->addMonth() : $now->copy();

            [$effStart, $effEnd] = Holiday::getEffectiveCycleDates($cycleDate->month, $cycleDate->year);

            $startDate = $startDate ?: $effStart->format('Y-m-d');
            $endDate = $endDate ?: $effEnd->format('Y-m-d');
        }

        return [$startDate, $endDate];
    }

    /** @return array<string, mixed> */
    private function formatPresenceRow(Presence $p): array
    {
        $origJamMasuk = $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '—';
        $jamMasuk = $origJamMasuk;
        $jamPulang = $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '—';

        $actual = 0;
        if ($p->jam_masuk && $p->jam_pulang) {
            $actual = Carbon::parse($p->tanggal.' '.$p->jam_masuk)->diffInMinutes(Carbon::parse($p->tanggal.' '.$p->jam_pulang));
        }
        $transferred = OvertimeTransfer::where('presence_id', $p->id)->sum('durasi_menit');
        $total = $actual + $transferred;

        if ($actual === 0 && $total > 0 && $p->jam_masuk) {
            $start = Carbon::parse($p->tanggal.' '.$p->jam_masuk);
            $end = $start->copy()->addMinutes($total);
            $jamMasuk = $start->format('H:i');
            $jamPulang = $end->format('H:i');
        }

        $status = 'tepat';
        if ($origJamMasuk === '—') {
            $status = 'izin';
        } elseif ($p->jam_masuk && strtotime($origJamMasuk) > strtotime('08:00')) {
            $status = 'telat';
        }

        return [
            'id' => $p->id,
            'user_id' => $p->user_id,
            'tgl' => Carbon::parse($p->tanggal)->locale('id')->translatedFormat('l, d M Y'),
            'nama' => $p->user?->name ?? 'Unknown',
            'cin' => $jamMasuk,
            'cout' => $jamPulang,
            'raw_cin' => $p->jam_masuk,
            'raw_cout' => $p->jam_pulang,
            'durasi' => $p->total_jam ?? '—',
            'status' => $status,
            'pekerjaan' => $p->pekerjaan ?? 'Tidak ada deskripsi',
            'foto' => $p->foto ? url('storage/'.$p->foto) : null,
        ];
    }

    /** @return array<string, array<string, string>> */
    private function getStatusMap(): array
    {
        return [
            'tepat' => ['label' => 'Tepat Waktu', 'cls' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            'telat' => ['label' => 'Terlambat', 'cls' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
            'lembur' => ['label' => 'Lembur', 'cls' => 'bg-telkom-100 text-telkom-700 dark:bg-telkom-900/30 dark:text-telkom-400'],
            'izin' => ['label' => 'Izin', 'cls' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
        ];
    }

    private function getDayName(int $dayOfWeekIso): string
    {
        return match ($dayOfWeekIso) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
            default => '-',
        };
    }

    private function getMonthName(int $month): string
    {
        return match ($month) {
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            default => '',
        };
    }

    private function formatPeriodLabel(Carbon $start, Carbon $end): string
    {
        return $start->format('d').' '.$this->getMonthName((int) $start->format('m')).' '.$start->format('Y')
            .' - '
            .$end->format('d').' '.$this->getMonthName((int) $end->format('m')).' '.$end->format('Y');
    }
}
