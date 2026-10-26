<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Unit;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $roleFilter = $request->query('role');

        $query = User::with(['roles', 'unit'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($roleFilter) {
            $query->role($roleFilter);
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::pluck('name');
        $units = Unit::orderBy('name')->get();

        return view('pages.admin.users.index', compact('users', 'roles', 'units', 'status', 'roleFilter', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'nim' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'type' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:100'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $randomPassword = $validated['password'] ?? Str::password(12);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'nim' => $validated['nim'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'type' => $validated['type'] ?? 'Staff',
            'position' => $validated['position'] ?? null,
            'unit_id' => $validated['unit_id'] ?? null,
            'is_active' => true,
            'password' => Hash::make($randomPassword),
        ]);

        $user->assignRole($validated['role']);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'user.created',
            'model_type' => User::class,
            'model_id' => $user->id,
            'new_values' => ['username' => $user->username, 'role' => $validated['role']],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', "Pengguna {$user->name} berhasil ditambahkan!");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nim' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'type' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:100'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $policy = new UserPolicy;
        if ($user->hasRole('super-admin') && $validated['role'] !== 'super-admin') {
            if (! $policy->demote(Auth::user(), $user)) {
                return back()->with('error', 'Tidak dapat mengubah role akun super-admin ini.');
            }
        }

        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'nim' => $validated['nim'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'type' => $validated['type'] ?? 'Staff',
            'position' => $validated['position'] ?? null,
            'unit_id' => $validated['unit_id'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->syncRoles([$validated['role']]);

        return back()->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        $policy = new UserPolicy;

        if ($user->is_active && ! $policy->deactivate(Auth::user(), $user)) {
            return back()->with('error', 'Tindakan deaktivasi ditolak oleh guardrail keamanan.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $user->is_active ? 'user.activated' : 'user.deactivated',
            'model_type' => User::class,
            'model_id' => $user->id,
            'new_values' => ['is_active' => $user->is_active],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$statusText}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $policy = new UserPolicy;

        if (! $policy->delete(Auth::user(), $user)) {
            return back()->with('error', 'Tindakan penghapusan ditolak oleh guardrail keamanan.');
        }

        $name = $user->name;
        $user->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'user.deleted',
            'model_type' => User::class,
            'model_id' => $user->id,
            'old_values' => ['username' => $user->username],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', "Akun {$name} berhasil dihapus.");
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['nullable', 'file', 'mimes:csv,txt', 'max:2048'],
            'file' => ['nullable', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file = $request->file('csv_file') ?? $request->file('file');
        if (! $file) {
            return back()->with('error', 'Berkas CSV harus diunggah.');
        }

        $handle = fopen($file->getRealPath(), 'r');

        if (! $handle) {
            return back()->with('error', 'Gagal membaca berkas CSV.');
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);

            return back()->with('error', 'Berkas CSV kosong.');
        }

        // Normalize header keys
        $header = array_map(fn ($col) => strtolower(trim($col)), $header);

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($header)) {
                $skipped++;

                continue;
            }

            $data = array_combine($header, array_map('trim', $row));

            $username = $data['username'] ?? null;
            $name = $data['name'] ?? null;
            $email = ! empty($data['email']) ? $data['email'] : null;

            if (! $username || ! $name || User::where('username', $username)->exists()) {
                $skipped++;

                continue;
            }

            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'nim' => $data['nim'] ?? null,
                'phone' => $data['phone'] ?? null,
                'type' => $data['type'] ?? 'Staff',
                'position' => $data['position'] ?? null,
                'password' => Hash::make(Str::password(12)),
                'is_active' => true,
            ]);

            $role = ! empty($data['role']) && Role::where('name', $data['role'])->exists()
                ? $data['role']
                : 'staff';

            $user->assignRole($role);
            $imported++;
        }

        fclose($handle);

        return back()->with('success', "Import CSV selesai: {$imported} akun diimpor, {$skipped} dilewati.");
    }
}
