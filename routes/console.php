<?php

use App\Models\Holiday;
use App\Models\Presence;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Every January 1st at midnight, check if we need to seed the next 8 years.
// If the last holiday in the DB is less than 8 years away, re-run the seeder.
Schedule::call(function () {
    $lastHolidayYear = Holiday::max(DB::raw('YEAR(date)'));
    $currentYear = now()->year;

    if (! $lastHolidayYear || $lastHolidayYear < ($currentYear + 8)) {
        Artisan::call('db:seed', ['--class' => 'HolidaySeeder', '--force' => true]);
    }
})->yearlyOn(1, 1, '00:00')->name('check-and-seed-holidays')->withoutOverlapping();

// Auto-checkout at jam_pulang
Schedule::call(function () {
    $jamPulangSetting = Setting::where('key', 'jam_pulang')->value('value');
    if (! $jamPulangSetting) {
        return;
    }

    $jamPulangTimeStr = Carbon::parse($jamPulangSetting)->format('H:i');
    $currentTimeStr = now()->format('H:i');

    if ($currentTimeStr >= $jamPulangTimeStr) {
        $today = now()->format('Y-m-d');
        $presences = Presence::where('tanggal', $today)
            ->whereNull('jam_pulang')
            ->get();

        foreach ($presences as $presence) {
            if ($presence->jam_masuk) {
                $jamMasuk = Carbon::parse($presence->tanggal.' '.$presence->jam_masuk);
                $autoCheckoutTime = Carbon::parse($today.' '.$jamPulangTimeStr.':00');

                if ($jamMasuk->greaterThan($autoCheckoutTime)) {
                    $autoCheckoutTime = now();
                }

                $totalDetik = $jamMasuk->diffInSeconds($autoCheckoutTime);
                $jam = floor($totalDetik / 3600);
                $menit = floor(($totalDetik % 3600) / 60);

                $presence->update([
                    'jam_pulang' => $autoCheckoutTime->format('H:i:s'),
                    'total_jam' => "{$jam}j {$menit}m",
                ]);
            }
        }
    }
})->everyMinute()->name('auto-checkout')->withoutOverlapping();
