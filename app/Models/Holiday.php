<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['date', 'name', 'is_national'];

    protected $casts = [
        'date' => 'date',
        'is_national' => 'boolean',
    ];

    /**
     * Get the adjusted start and end dates for a presence cycle.
     * The normal cycle is 16th of previous month to 15th of current month.
     * If these dates fall on a weekend or holiday, they adjust to the closest inner weekday.
     */
    public static function getEffectiveCycleDates(int $month, int $year): array
    {
        $baseStart = Carbon::create($year, $month, 15)->subMonth()->addDay(); // 16th of previous month
        $baseEnd = Carbon::create($year, $month, 15); // 15th of current month

        // Fetch all holidays around this period to minimize DB queries
        $holidays = self::whereBetween('date', [
            $baseStart->copy()->subDays(10)->format('Y-m-d'),
            $baseEnd->copy()->addDays(10)->format('Y-m-d'),
        ])->pluck('date')->map(fn ($d) => is_string($d) ? $d : $d->format('Y-m-d'))->toArray();

        // Adjust start date forward
        while ($baseStart->isWeekend() || in_array($baseStart->format('Y-m-d'), $holidays)) {
            $baseStart->addDay();
        }

        // Adjust end date backward
        while ($baseEnd->isWeekend() || in_array($baseEnd->format('Y-m-d'), $holidays)) {
            $baseEnd->subDay();
        }

        return [$baseStart, $baseEnd];
    }

    /**
     * Check if a given date is a holiday or weekend.
     */
    public static function isOff(string $date): bool
    {
        $carbon = Carbon::parse($date);

        if ($carbon->isWeekend()) {
            return true;
        }

        return self::where('date', $date)->exists();
    }

    /**
     * Get all holiday dates in a given range.
     *
     * @return array<string>
     */
    public static function getHolidayDatesInRange(string $start, string $end): array
    {
        return self::whereBetween('date', [$start, $end])
            ->pluck('date')
            ->map(fn ($d) => is_string($d) ? $d : $d->format('Y-m-d'))
            ->toArray();
    }
}
