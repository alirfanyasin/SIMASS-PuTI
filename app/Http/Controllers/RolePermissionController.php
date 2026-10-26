<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of users and their roles/permissions.
     */
    public function index(): View
    {
        $users = User::with(['roles', 'permissions'])->orderBy('name')->get();

        return view('pages.role-permission', compact('users'));
    }

    /**
     * Show the dedicated form for editing a user's role and app-categorized permissions.
     */
    public function edit(User $user): View
    {
        $user->load(['roles', 'permissions']);

        // Main roles only: student-staff, staff, super-admin
        $mainRoles = [
            'student-staff' => [
                'name' => 'student-staff',
                'label' => 'Student Staff',
                'desc' => 'Asisten mahasiswa untuk operasional harian presensi & tugas teknis.',
                'color' => 'amber',
            ],
            'staff' => [
                'name' => 'staff',
                'label' => 'Staff',
                'desc' => 'Pegawai tetap / tenaga kependidikan dengan akses layanan operasional.',
                'color' => 'emerald',
            ],
            'super-admin' => [
                'name' => 'super-admin',
                'label' => 'Super Admin',
                'desc' => 'Akses penuh tanpa batas ke seluruh modul, pengguna, dan pengaturan sistem.',
                'color' => 'telkom',
            ],
        ];

        // Permissions metadata and application grouping
        $permissionMeta = [
            // Presensi PuTI
            'view-presence' => ['app' => 'Presensi PuTI', 'label' => 'Lihat Data Presensi', 'desc' => 'Melihat daftar kehadiran pegawai'],
            'create-presence' => ['app' => 'Presensi PuTI', 'label' => 'Presensi Mandiri', 'desc' => 'Melakukan check-in dan check-out mandiri'],
            'manage-presence' => ['app' => 'Presensi PuTI', 'label' => 'Kelola Presensi', 'desc' => 'Menyetujui dan mengoreksi data presensi'],
            'view-presence-history' => ['app' => 'Presensi PuTI', 'label' => 'Riwayat Presensi', 'desc' => 'Melihat rekap riwayat kehadiran'],
            'manage-overtime' => ['app' => 'Presensi PuTI', 'label' => 'Kelola Jam Lembur', 'desc' => 'Menyetujui dan mengatur jam lembur'],
            'view-holiday' => ['app' => 'Presensi PuTI', 'label' => 'Lihat Hari Libur', 'desc' => 'Melihat kalender hari libur operasional'],
            'manage-holiday' => ['app' => 'Presensi PuTI', 'label' => 'Kelola Hari Libur', 'desc' => 'Menambah dan mengatur hari libur kalender'],

            // Admin Console
            'access-admin' => ['app' => 'Admin Console', 'label' => 'Akses Admin Console', 'desc' => 'Membuka dan menggunakan konsol admin pusat'],
            'manage-users' => ['app' => 'Admin Console', 'label' => 'Kelola Akun Pengguna', 'desc' => 'Tambah, ubah data, reset password, dan status akun'],
            'manage-modules' => ['app' => 'Admin Console', 'label' => 'Kelola Modul Portal', 'desc' => 'Mengatur modul portal yang aktif dan visibilitasnya'],
            'manage-master-data' => ['app' => 'Admin Console', 'label' => 'Kelola Data Master & SLA', 'desc' => 'Mengatur unit kerja, lokasi kampus, dan target SLA'],
            'view-audit-logs' => ['app' => 'Admin Console', 'label' => 'Lihat Audit Logs', 'desc' => 'Melihat log rekam jejak aktivitas pengguna'],
            'view-roles' => ['app' => 'Admin Console', 'label' => 'Lihat Role & Peran', 'desc' => 'Melihat daftar peran dan hak akses pengguna'],
            'manage-roles' => ['app' => 'Admin Console', 'label' => 'Kelola Role & Peran', 'desc' => 'Mengubah peran dan izin akses pengguna sistem'],
            'view-settings' => ['app' => 'Admin Console', 'label' => 'Lihat Pengaturan Sistem', 'desc' => 'Melihat konfigurasi dan parameter sistem'],
            'manage-settings' => ['app' => 'Admin Console', 'label' => 'Kelola Pengaturan Sistem', 'desc' => 'Mengubah parameter konfigurasi dan sistem'],
        ];

        $allPermissions = Permission::orderBy('name')->get();
        $permissionsByApp = [];

        foreach ($allPermissions as $perm) {
            $name = $perm->name;
            $meta = $permissionMeta[$name] ?? null;

            if ($meta) {
                $appName = $meta['app'];
                $label = $meta['label'];
                $desc = $meta['desc'];
            } elseif (str_contains($name, 'presence') || str_contains($name, 'overtime') || str_contains($name, 'holiday')) {
                $appName = 'Presensi PuTI';
                $label = ucwords(str_replace(['-', '_'], ' ', $name));
                $desc = 'Hak akses modul Presensi PuTI';
            } elseif (str_contains($name, 'ticket') || str_contains($name, 'luna')) {
                $appName = 'e-Ticket PuTI';
                $label = ucwords(str_replace(['-', '_'], ' ', $name));
                $desc = 'Hak akses modul E-Ticketing';
            } elseif (str_contains($name, 'inventory')) {
                $appName = 'SIMASS Inventaris';
                $label = ucwords(str_replace(['-', '_'], ' ', $name));
                $desc = 'Hak akses modul Inventory';
            } else {
                $appName = 'Admin Console';
                $label = ucwords(str_replace(['-', '_'], ' ', $name));
                $desc = 'Hak akses administrasi sistem';
            }

            $permissionsByApp[$appName][] = [
                'name' => $name,
                'label' => $label,
                'desc' => $desc,
            ];
        }

        // All system applications including incoming / coming-soon apps
        $applications = [
            'Presensi PuTI' => [
                'name' => 'Presensi PuTI',
                'slug' => 'presensi-puti',
                'icon' => 'calendar-check',
                'status' => 'active',
                'description' => 'Sistem absensi berbasis AI Face Recognition & Dynamic Geofencing terenkripsi.',
                'permissions' => $permissionsByApp['Presensi PuTI'] ?? [],
            ],
            'Admin Console' => [
                'name' => 'Admin Console',
                'slug' => 'admin-console',
                'icon' => 'shield',
                'status' => 'active',
                'description' => 'Pusat manajemen hak akses, master data, monitoring SLA, dan konfigurasi portal.',
                'permissions' => $permissionsByApp['Admin Console'] ?? [],
            ],
            'e-Ticket PuTI' => [
                'name' => 'e-Ticket PuTI',
                'slug' => 'eticket-puti',
                'icon' => 'ticket',
                'status' => 'active',
                'description' => 'Platform helpdesk, pelaporan insiden, dan permintaan layanan dengan Luna AI.',
                'permissions' => $permissionsByApp['e-Ticket PuTI'] ?? [],
            ],
            'Neing' => [
                'name' => 'Neing',
                'slug' => 'ning-ai',
                'icon' => 'bot',
                'status' => 'coming_soon',
                'description' => 'Agen AI percakapan berbasis Telegram & WhatsApp untuk interaksi layanan instan.',
                'permissions' => [],
            ],
            'WebOsistant' => [
                'name' => 'WebOsistant',
                'slug' => 'webosistant',
                'icon' => 'globe',
                'status' => 'coming_soon',
                'description' => 'CMS generator dan manajemen situs lab/unit kampus berbasis visual modern.',
                'permissions' => [],
            ],
            'SIMASS Inventaris' => [
                'name' => 'SIMASS Inventaris',
                'slug' => 'simass-inventaris',
                'icon' => 'package',
                'status' => 'coming_soon',
                'description' => 'Pencatatan aset perangkat keras, peminjaman barang, dan log audit lab.',
                'permissions' => [],
            ],
        ];

        $userRoles = $user->roles->pluck('name')->toArray();
        $currentRole = $userRoles[0] ?? 'staff';
        $userDirectPermissions = $user->permissions->pluck('name')->toArray();
        $userAllPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        return view('pages.role-permission-edit', compact(
            'user',
            'mainRoles',
            'applications',
            'permissionsByApp',
            'currentRole',
            'userDirectPermissions',
            'userAllPermissions'
        ));
    }

    /**
     * Update the user's role and direct permissions.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'user_role' => ['required', 'string', 'in:student-staff,staff,super-admin'],
            'user_permissions' => ['nullable', 'array'],
            'user_permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $policy = new UserPolicy;
        if ($user->hasRole('super-admin') && $validated['user_role'] !== 'super-admin') {
            if (! $policy->demote(Auth::user(), $user)) {
                return back()->with('error', 'Tidak dapat mengubah role akun super-admin ini (proteksi akun super-admin terakhir atau akun sendiri).');
            }
        }

        // Sync role
        $user->syncRoles([$validated['user_role']]);

        // Sync direct permissions
        $permissions = $validated['user_permissions'] ?? [];
        $user->syncPermissions($permissions);

        return redirect()->route('role-permission')->with('success', "Hak akses pengguna {$user->name} berhasil diperbarui!");
    }
}
