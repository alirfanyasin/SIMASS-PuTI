<?php

namespace App\Http\Controllers;


use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('pages.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'office_latitude' => 'required|numeric',
            'office_longitude' => 'required|numeric',
            'office_radius' => 'required|numeric|min:1',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
        ]);

        Setting::updateOrCreate(['key' => 'office_latitude'], ['value' => $request->office_latitude]);
        Setting::updateOrCreate(['key' => 'office_longitude'], ['value' => $request->office_longitude]);
        Setting::updateOrCreate(['key' => 'office_radius'], ['value' => $request->office_radius]);
        Setting::updateOrCreate(['key' => 'jam_masuk'], ['value' => $request->jam_masuk]);
        Setting::updateOrCreate(['key' => 'jam_pulang'], ['value' => $request->jam_pulang]);

        return back()->with('status', 'Pengaturan lokasi berhasil disimpan.');
    }
}
