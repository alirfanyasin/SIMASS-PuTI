<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['date', 'name', 'is_national'];

    /**
     * Get the adjusted start and end dates for a presence cycle.
     * The normal cycle is 16th of previous month to 15th of current month.
     * If these dates fall on a weekend or holiday, they adjust to the closest inner weekday.
     */
    public static function getEffectiveCycleDates($month, $year)
    {
        $baseStart = \Carbon\Carbon::create($year, $month, 15)->subMonth()->addDay(); // 16th of previous month
        $baseEnd = \Carbon\Carbon::create($year, $month, 15); // 15th of current month

        // Fetch all holidays around this period to minimize DB queries
        $holidays = self::whereBetween('date', [
            $baseStart->copy()->subDays(10)->format('Y-m-d'),
            $baseEnd->copy()->addDays(10)->format('Y-m-d')
        ])->pluck('date')->toArray();

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
}
