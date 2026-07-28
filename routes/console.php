<?php

use App\Models\Holiday;
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
