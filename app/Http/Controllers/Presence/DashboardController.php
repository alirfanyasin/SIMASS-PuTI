<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use App\Models\OvertimeTransfer;
use App\Models\Presence;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $today = Carbon::today()->format('Y-m-d');

        $todayPresence = Presence::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // Get this month's stats
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        $presences = Presence::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->get();

        $jamMasukLimit = Setting::where('key', 'jam_masuk')->value('value') ?? '08:30:00';
        // Ensure it has seconds for comparison
        if (strlen($jamMasukLimit) === 5) {
            $jamMasukLimit .= ':00';
        }

        $stats = [
            'hadir' => $presences->count(),
            'tepat_waktu' => $presences->filter(fn ($p) => $p->jam_masuk && $p->jam_masuk <= $jamMasukLimit)->count(),
            'terlambat' => $presences->filter(fn ($p) => $p->jam_masuk && $p->jam_masuk > $jamMasukLimit)->count(),
            'izin' => 0, // Placeholder if no permission model exists yet
        ];

        if ($user->hasRole('student-staff') || $user->can('manage-presence')) {
            $recentPresences = Presence::with('user')->whereHas('user', function ($q) {
                $q->role('student-staff');
            })
                ->orderBy('tanggal', 'desc')
                ->take(10)
                ->get();
        } else {
            $recentPresences = Presence::with('user')->where('user_id', $user->id)
                ->orderBy('tanggal', 'desc')
                ->take(10)
                ->get();
        }

        $activities = collect();
        foreach ($recentPresences as $p) {
            if ($p->jam_masuk) {
                $activities->push((object) [
                    'type' => 'in',
                    'time' => $p->jam_masuk,
                    'date' => $p->tanggal,
                    'user' => $p->user,
                    'timestamp' => $p->tanggal.' '.$p->jam_masuk,
                ]);
            }
            if ($p->jam_pulang) {
                $activities->push((object) [
                    'type' => 'out',
                    'time' => $p->jam_pulang,
                    'date' => $p->tanggal,
                    'user' => $p->user,
                    'timestamp' => $p->tanggal.' '.$p->jam_pulang,
                ]);
            }
        }

        $recentActivity = $activities->sortByDesc('timestamp')->take(5)->values();

        $overtimeSaldo = Overtime::where('user_id', $user->id)->sum('sisa_menit');

        // Prepare Chart Data
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyPresences = Presence::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();
        $weeklyOvertimes = Overtime::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        $weeklyHadir = [];
        $weeklyLembur = [];
        $weeklyLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

        for ($i = 0; $i < 7; $i++) {
            $date = clone $startOfWeek;
            $date->addDays($i)->format('Y-m-d');
            $p = $weeklyPresences->firstWhere('tanggal', $date->format('Y-m-d'));
            $o = $weeklyOvertimes->firstWhere('tanggal', $date->format('Y-m-d'));

            $hours = 0;
            if ($p && $p->jam_masuk && $p->jam_pulang) {
                $actual = Carbon::parse($p->jam_masuk)->diffInMinutes(Carbon::parse($p->jam_pulang));
                $transferred = OvertimeTransfer::where('presence_id', $p->id)->sum('durasi_menit');
                $diffInMinutes = $actual + $transferred;

                $h = floor($diffInMinutes / 60);
                $remainder = $diffInMinutes % 60;

                if ($remainder > 30) {
                    $h += 1;
                }

                if ($h > 8) {
                    $h = 8;
                }
                $hours = $h;
            }
            $weeklyHadir[] = $hours;
            $weeklyLembur[] = $o ? round($o->durasi_menit / 60, 1) : 0;
        }

        // Monthly
        $monthlyHadir = [];
        $monthlyLembur = [];
        $monthlyLabels = [];
        $daysInMonth = Carbon::now()->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::now()->setDay($i)->format('Y-m-d');
            $p = $presences->firstWhere('tanggal', $date);
            $o = Overtime::where('user_id', $user->id)->where('tanggal', $date)->first();

            $hours = 0;
            if ($p && $p->jam_masuk && $p->jam_pulang) {
                $actual = Carbon::parse($p->jam_masuk)->diffInMinutes(Carbon::parse($p->jam_pulang));
                $transferred = OvertimeTransfer::where('presence_id', $p->id)->sum('durasi_menit');
                $diffInMinutes = $actual + $transferred;

                $h = floor($diffInMinutes / 60);
                $remainder = $diffInMinutes % 60;

                if ($remainder > 30) {
                    $h += 1;
                }

                if ($h > 8) {
                    $h = 8;
                }
                $hours = $h;
            }
            $monthlyHadir[] = $hours;
            $monthlyLembur[] = $o ? round($o->durasi_menit / 60, 1) : 0;
            $monthlyLabels[] = $i;
        }

        $chartData = [
            'weekly' => [
                'labels' => $weeklyLabels,
                'hadir' => $weeklyHadir,
                'lembur' => $weeklyLembur,
            ],
            'monthly' => [
                'labels' => $monthlyLabels,
                'hadir' => $monthlyHadir,
                'lembur' => $monthlyLembur,
            ],
        ];

        return view('pages.presence.dashboard', compact('todayPresence', 'stats', 'recentActivity', 'overtimeSaldo', 'chartData'));
    }
}
