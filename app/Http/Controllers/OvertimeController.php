<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $overtimeQuery = \App\Models\Overtime::with('user')->orderBy('tanggal', 'desc');
        $transferQuery = \App\Models\OvertimeTransfer::with(['user', 'presence'])->orderBy('tanggal_transfer', 'desc');
        
        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $overtimeQuery->where('user_id', $request->user_id);
            $transferQuery->where('user_id', $request->user_id);
        }

        if ($request->filled('bulan')) {
            // bulan is e.g. "Juli 2026"
            $parts = explode(' ', $request->bulan);
            if (count($parts) == 2) {
                $monthNames = ['Januari'=>1,'Februari'=>2,'Maret'=>3,'April'=>4,'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12];
                $monthStr = $parts[0];
                $yearStr = $parts[1];
                if (isset($monthNames[$monthStr])) {
                    $m = str_pad($monthNames[$monthStr], 2, '0', STR_PAD_LEFT);
                    $likeStr = "{$yearStr}-{$m}%";
                    $overtimeQuery->where('tanggal', 'like', $likeStr);
                    $transferQuery->where('tanggal_transfer', 'like', $likeStr);
                }
            }
        }

        $allOvertimes = $overtimeQuery->get();
        $allTransfers = $transferQuery->get();

        $totalAkumulasiMenit = $allOvertimes->sum('durasi_menit');
        $totalSaldoMenit = $allOvertimes->sum('sisa_menit');
        $totalAlokasiMenit = $allTransfers->sum('durasi_menit');

        $saldoLembur = $allOvertimes->map(function($o) {
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

        $riwayatAlokasi = $allTransfers->map(function($t) {
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
            'penuh' => [
                'label' => 'Tersedia Penuh',
                'cls' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            ],
            'sebagian' => [
                'label' => 'Terpakai Sebagian',
                'cls' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            ],
            'habis' => [
                'label' => 'Habis Terpakai',
                'cls' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
            ],
        ];

        $users = \App\Models\User::whereNotNull('position')->get();

        return view('pages.app.overtime', compact(
            'totalAkumulasiMenit', 'totalSaldoMenit', 'totalAlokasiMenit',
            'saldoLembur', 'riwayatAlokasi', 'statusMap', 'users'
        ));
    }
}
