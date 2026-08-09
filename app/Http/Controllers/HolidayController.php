<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HolidayController extends Controller
{
    public function index(Request $request): View
    {
        $year = (int) $request->input('year', Carbon::now()->year);

        $holidays = Holiday::whereYear('date', $year)
            ->orderBy('date')
            ->get();

        $years = range(Carbon::now()->year - 1, Carbon::now()->year + 1);

        return view('pages.holiday.index', compact('holidays', 'year', 'years'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date', 'unique:holidays,date'],
            'name' => ['required', 'string', 'max:255'],
            'is_national' => ['boolean'],
        ]);

        Holiday::create($data);

        return back()->with('status', 'Hari libur berhasil ditambahkan.');
    }

    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date', 'unique:holidays,date,'.$holiday->id],
            'name' => ['required', 'string', 'max:255'],
            'is_national' => ['boolean'],
        ]);

        $holiday->update($data);

        return back()->with('status', 'Hari libur berhasil diperbarui.');
    }

    public function destroy(Holiday $holiday): RedirectResponse
    {
        $holiday->delete();

        return back()->with('status', 'Hari libur berhasil dihapus.');
    }
}
