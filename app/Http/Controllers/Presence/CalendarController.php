<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Holiday;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $month = (int)$month;
        $year = (int)$year;

        $date = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;

        $startDayOfWeek = $date->dayOfWeekIso; // 1 (Mon) to 7 (Sun)

        $holidays = Holiday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy('date');

        $calendarData = [];

        // Pad beginning of the grid
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            $calendarData[] = null;
        }

        // Add actual days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($year, $month, $day);
            $dateString = $currentDate->format('Y-m-d');
            $isWeekend = $currentDate->isWeekend();

            $holidayDesc = null;
            if ($holidays->has($dateString)) {
                $holidayDesc = $holidays->get($dateString)->name;
            }

            $calendarData[] = [
                'day' => $day,
                'date' => $dateString,
                'is_weekend' => $isWeekend,
                'holiday' => $holidayDesc,
                'is_off' => $isWeekend || $holidayDesc,
                'is_today' => $dateString === Carbon::now()->format('Y-m-d'),
            ];
        }

        $prevMonthDate = $date->copy()->subMonth();
        $nextMonthDate = $date->copy()->addMonth();

        $monthNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $currentMonthName = $monthNames[$month];

        return view('pages.presence.calendar', compact(
            'calendarData',
            'currentMonthName',
            'year',
            'month',
            'prevMonthDate',
            'nextMonthDate'
        ));
    }
}
