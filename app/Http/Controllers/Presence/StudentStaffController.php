<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class StudentStaffController extends Controller
{
    public function index(): View
    {
        $staffList = User::whereNotNull('position')
            ->get()
            ->map(function (User $staff) {
                return [
                    'name' => $staff->name,
                    'nim' => '-',
                    'email' => $staff->email ?? strtolower(str_replace(' ', '', $staff->name)).'@student.telkomuniversity.ac.id',
                    'avatar' => null,
                    'role' => $staff->position ?? '-',
                    'spatie_role' => $staff->getRoleNames()->first() ?? '-',
                    'face_registered' => ! is_null($staff->face_descriptor),
                ];
            });

        return view('pages.presence.student-staff', compact('staffList'));
    }
}
