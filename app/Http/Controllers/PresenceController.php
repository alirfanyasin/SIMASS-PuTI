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

        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

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

        return view('pages.app.presence-list', compact('presensiData', 'statusMap', 'users'));
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
                $prevMonth = $payMonth == 1 ? 12 : $payMonth - 1;
                $prevYear = $payMonth == 1 ? $payYear - 1 : $payYear;
                $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                
                $grouped[$periodKey] = [
                    'id' => $periodKey,
                    'title' => $monthNames[$payMonth] . ' ' . $payYear,
                    'period' => "16 " . $monthNames[$prevMonth] . " $prevYear - 15 " . $monthNames[$payMonth] . " $payYear",
                    'total' => 0,
                    'staffs' => [],
                    'detailData' => []
                ];
            }
            $grouped[$periodKey]['total']++;
            $grouped[$periodKey]['staffs'][$p->user_id] = true;
            $grouped[$periodKey]['detailData'][] = [
                'nama' => $p->user ? $p->user->name : 'Unknown',
                'hari' => $p->hari ?? '-',
                'tgl' => date('d M Y', strtotime($p->tanggal)),
                'waktu' => ($p->jam_masuk ?? '-') . ' - ' . ($p->jam_pulang ?? '-'),
                'jam' => $p->total_jam ?? '-',
            ];
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
        } else {
            $detailData = [];
            $detailTitle = '';
            $detailPeriod = '';
            $viewMode = 'list';
        }
        
        return view('pages.app.presence-history', compact('months', 'detailData', 'detailTitle', 'detailPeriod', 'viewMode'));
    }
}
