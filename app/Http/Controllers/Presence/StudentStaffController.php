<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;

class StudentStaffController extends Controller
{
    public function index()
    {
        $staffList = \App\Models\User::whereNotNull('position')->get()->map(function ($staff) {
            return [
                'name' => $staff->name,
                'nim' => '-',
                'email' => $staff->email ?? (strtolower(str_replace(' ', '', $staff->name)) . '@student.telkomuniversity.ac.id'),
                'avatar' => null,
                'role' => $staff->position ?? '-',
                'face_registered' => !is_null($staff->face_descriptor),
            ];
        });

        return view('pages.presence.student-staff', compact('staffList'));
    }
}
