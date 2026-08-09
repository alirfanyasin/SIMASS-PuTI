<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        // Load users with their roles and permissions
        $users = User::with(['roles', 'permissions'])->get();

        // Get all roles from DB
        $roles = Role::pluck('name')->toArray();

        // Get all permissions and group them logically for display
        $allPermissions = Permission::pluck('name')->toArray();
        $permissions = [];
        
        foreach ($allPermissions as $perm) {
            if (str_contains($perm, 'presence') || str_contains($perm, 'overtime')) {
                $permissions['Presensi'][] = $perm;
            } elseif (str_contains($perm, 'holiday')) {
                $permissions['Holiday'][] = $perm;
            } elseif (str_contains($perm, 'settings') || str_contains($perm, 'role')) {
                $permissions['Pengaturan'][] = $perm;
            } else {
                $permissions['Lainnya'][] = $perm;
            }
        }

        return view('pages.role-permission', compact('users', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'user_role' => 'required|string|exists:roles,name',
            'user_permissions' => 'nullable|array',
            'user_permissions.*' => 'string|exists:permissions,name',
        ]);

        // Sync Role
        $user->syncRoles([$request->user_role]);

        // Sync Direct Permissions
        // If a user has a role, they already inherit its permissions.
        // syncPermissions will apply specific permissions directly to the user.
        $permissions = $request->user_permissions ?? [];
        $user->syncPermissions($permissions);

        return redirect()->route('role-permission')->with('success', 'Perubahan hak akses berhasil disimpan!');
    }
}
