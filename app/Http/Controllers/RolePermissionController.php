<?php

namespace App\Http\Controllers;

use App\Models\User;

class RolePermissionController extends Controller
{
    public function index()
    {
        $users = User::all();

        // Default fallback if no users in db yet
        if ($users->isEmpty()) {
            $users = collect([
                (object) [
                    'id' => 1,
                    'name' => 'Andi Pratama',
                    'email' => 'andipratama@telkomuniversity.ac.id',
                    'position' => 'Dosen - FIT',
                    'type' => 'dosen',
                ],
                (object) [
                    'id' => 2,
                    'name' => 'Irfan Yasin',
                    'email' => 'irfanyasin@student.telkomuniversity.ac.id',
                    'position' => 'Student Staff',
                    'type' => 'student',
                ],
                (object) [
                    'id' => 3,
                    'name' => 'Fitriani Latifah',
                    'email' => 'fitriani@telkomuniversity.ac.id',
                    'position' => 'Staff Administrasi',
                    'type' => 'staff',
                ],
            ]);
        }

        $roles = ['Admin', 'Staff', 'Student Staff', 'User'];
        $permissions = [
            'Presensi' => ['view-presence', 'manage-presence', 'view-history', 'manage-overtime'],
            'Ticketing' => ['view-tickets', 'create-tickets', 'manage-tasks', 'view-history-tickets'],
            'Pengaturan' => ['view-settings', 'manage-roles'],
        ];

        return view('pages.role-permission', compact('users', 'roles', 'permissions'));
    }
}
