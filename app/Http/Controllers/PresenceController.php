<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function presence()
    {
        return view('pages.app.presence');
    }


    public function presenceList(Request $request)
    {
        $query = \App\Models\Presence::with('user')->orderBy('tanggal', 'desc');

        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        // Default dates using Holiday effective cycle if not provided
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            $now = \Carbon\Carbon::now();
            $month = $now->month;
            $year = $now->year;
            
            // If today is <= 15, we are in the previous cycle
            if ($now->day <= 15) {
                // Actually cycle for current month ends on 15th. 
                // Wait, if today is June 10, the cycle is 16 May - 15 June. This is the "June" cycle (month=6).
                // So if day <= 15, we use current month.
            } else {
                // If today is June 18, we are in the July cycle (16 June - 15 July).
                $now->addMonth();
                $month = $now->month;
                $year = $now->year;
            }

            [$effStart, $effEnd] = \App\Models\Holiday::getEffectiveCycleDates($month, $year);
            
            $startDate = $startDate ?: $effStart->format('Y-m-d');
            $endDate = $endDate ?: $effEnd->format('Y-m-d');
        }

        $query->where('tanggal', '>=', $startDate);
        $query->where('tanggal', '<=', $endDate);

        $presences = $query->get();

        $presensiData = $presences->map(function($p) {
            $jamMasuk = $p->jam_masuk ?? '—';
            if ($jamMasuk !== '—' && strlen($jamMasuk) >= 5) {
                $jamMasuk = substr($jamMasuk, 0, 5);
            }
            $jamPulang = $p->jam_pulang ?? '—';
            if ($jamPulang !== '—' && strlen($jamPulang) >= 5) {
                $jamPulang = substr($jamPulang, 0, 5);
            }

            $status = 'tepat';
            if ($jamMasuk !== '—' && str_contains($jamMasuk, ':')) {
                $waktuMasuk = strtotime($jamMasuk);
                $batasTelat = strtotime('08:00');
                if ($waktuMasuk > $batasTelat) {
                    $status = 'telat';
                }
            } elseif ($jamMasuk === '—') {
                $status = 'izin';
            }

            return [
                'tgl' => date('d M Y', strtotime($p->tanggal)),
                'nama' => $p->user ? $p->user->name : 'Unknown',
                'cin' => $jamMasuk,
                'cout' => $jamPulang,
                'durasi' => $p->total_jam ?? '—',
                'status' => $status,
                'pekerjaan' => $p->pekerjaan ?? 'Tidak ada deskripsi',
                'foto' => $p->foto ? url('storage/' . $p->foto) : null,
            ];
        })->toArray();

        $statusMap = [
            'tepat' => [
                'label' => 'Tepat Waktu',
                'cls' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            ],
            'telat' => [
                'label' => 'Terlambat',
                'cls' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            ],
            'lembur' => [
                'label' => 'Lembur',
                'cls' => 'bg-telkom-100 text-telkom-700 dark:bg-telkom-900/30 dark:text-telkom-400',
            ],
            'izin' => [
                'label' => 'Izin',
                'cls' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            ],
        ];

        $users = \App\Models\User::whereNotNull('position')->get();

        return view('pages.app.presence-list', compact('presensiData', 'statusMap', 'users', 'startDate', 'endDate'));
    }

    public function presenceHistory()
    {
        $allPresences = \App\Models\Presence::with('user')->orderBy('tanggal', 'desc')->get();
        
        $grouped = [];
        foreach ($allPresences as $p) {
            $date = new \DateTime($p->tanggal);
            $day = (int)$date->format('d');
            $month = (int)$date->format('m');
            $year = (int)$date->format('Y');
            
            if ($day > 15) {
                $payMonth = $month == 12 ? 1 : $month + 1;
                $payYear = $month == 12 ? $year + 1 : $year;
            } else {
                $payMonth = $month;
                $payYear = $year;
            }
            
            $periodKey = sprintf("%04d-%02d", $payYear, $payMonth);
            if (!isset($grouped[$periodKey])) {
                [$effStart, $effEnd] = \App\Models\Holiday::getEffectiveCycleDates($payMonth, $payYear);

                $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                
                $grouped[$periodKey] = [
                    'id' => $periodKey,
                    'title' => $monthNames[$payMonth] . ' ' . $payYear,
                    'period' => $effStart->format('d') . ' ' . $monthNames[(int)$effStart->format('m')] . ' ' . $effStart->format('Y') . ' - ' . $effEnd->format('d') . ' ' . $monthNames[(int)$effEnd->format('m')] . ' ' . $effEnd->format('Y'),
                    'effStart' => $effStart->format('Y-m-d'),
                    'effEnd' => $effEnd->format('Y-m-d'),
                    'total' => 0,
                    'staffs' => [],
                    'detailData' => []
                ];
            }
            
            // Only count if within effective dates (filtering out weekends/holidays outside the adjusted range)
            $effStartStr = $grouped[$periodKey]['effStart'];
            $effEndStr = $grouped[$periodKey]['effEnd'];
            
            if ($p->tanggal >= $effStartStr && $p->tanggal <= $effEndStr) {
                $grouped[$periodKey]['total']++;
                $grouped[$periodKey]['staffs'][$p->user_id] = true;
                $grouped[$periodKey]['detailData'][] = [
                    'nama' => $p->user ? $p->user->name : 'Unknown',
                    'hari' => $p->hari ?? '-',
                    'tgl' => date('d M Y', strtotime($p->tanggal)),
                    'waktu' => ($p->jam_masuk ?? '-') . ' - ' . ($p->jam_pulang ?? '-'),
                    'jam' => $p->total_jam ?? '-',
                    'pekerjaan' => $p->pekerjaan ?? 'Tidak ada deskripsi',
                    'foto' => $p->foto ? url('storage/' . $p->foto) : null,
                ];
            }
        }
        
        krsort($grouped);
        
        $months = [];
        foreach ($grouped as $g) {
            $g['staff'] = count($g['staffs']) . ' Orang';
            $months[] = $g;
        }

        $selectedPeriod = request('period');
        if ($selectedPeriod && isset($grouped[$selectedPeriod])) {
            $detailData = $grouped[$selectedPeriod]['detailData'];
            $detailTitle = $grouped[$selectedPeriod]['title'];
            $detailPeriod = $grouped[$selectedPeriod]['period'];
            $viewMode = 'detail';
            
            $start = \Carbon\Carbon::parse($grouped[$selectedPeriod]['effStart']);
            $end = \Carbon\Carbon::parse($grouped[$selectedPeriod]['effEnd']);
            $calendarDays = [];
            while ($start <= $end) {
                $calendarDays[] = [
                    'day' => $start->format('j'),
                    'is_weekend' => $start->isWeekend(),
                    'is_holiday' => \App\Models\Holiday::where('date', $start->format('Y-m-d'))->exists()
                ];
                $start->addDay();
            }
        } else {
            $detailData = [];
            $detailTitle = '';
            $detailPeriod = '';
            $viewMode = 'list';
            $calendarDays = [];
        }
        
        return view('pages.app.presence-history', compact('months', 'detailData', 'detailTitle', 'detailPeriod', 'viewMode', 'calendarDays'));
    }
}
