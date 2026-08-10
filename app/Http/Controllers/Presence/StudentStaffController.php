<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class StudentStaffController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = User::whereNotNull('position');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $paginator = $query->paginate(7)->withQueryString();

        $staffList = collect($paginator->items())->map(function (User $staff) {
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'nim' => $staff->nim ?? '-',
                'username' => $staff->username,
                'phone' => $staff->phone ?? '-',
                'email' => $staff->email ?? strtolower(str_replace(' ', '', $staff->name)).'@student.telkomuniversity.ac.id',
                'avatar' => null,
                'role' => $staff->position ?? '-',
                'type' => $staff->type ?? '-',
                'spatie_role' => $staff->getRoleNames()->first() ?? '-',
                'face_registered' => ! is_null($staff->face_descriptor),
            ];
        });

        // Statistics
        $totalStudentStaff = User::where('type', 'Student Staff')->count();
        $totalStaff = User::where('type', 'Staf')->count();
        $totalFaceRegistered = User::whereNotNull('face_descriptor')->count();

        $activeCount = User::whereHas('presences')->count();
        if ($activeCount === 0) {
            $activeCount = User::whereNotNull('position')->count();
        }
        $totalUsers = User::whereNotNull('position')->count();
        $activePercentage = $totalUsers > 0 ? round(($activeCount / $totalUsers) * 100) : 100;

        return view('pages.presence.student-staff', compact(
            'staffList',
            'paginator',
            'totalStudentStaff',
            'totalStaff',
            'totalFaceRegistered',
            'activeCount',
            'activePercentage',
            'search'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nim' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'string', 'in:Student Staff,Staf'],
            'position' => ['required', 'string', 'max:100'],
            'password' => ['nullable', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'nim' => $data['nim'],
            'phone' => $data['phone'],
            'type' => $data['type'],
            'position' => $data['position'],
            'password' => Hash::make($data['password'] ?? 'password'),
        ]);

        if ($data['type'] === 'Student Staff') {
            $user->assignRole('student-staff');
        } else {
            $user->assignRole('staff');
        }

        return back()->with('status', 'Staff baru berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'nim' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'string', 'in:Student Staff,Staf'],
            'position' => ['required', 'string', 'max:100'],
            'password' => ['nullable', Password::defaults()],
        ]);

        $updateData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'nim' => $data['nim'],
            'phone' => $data['phone'],
            'type' => $data['type'],
            'position' => $data['position'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $oldType = $user->type;
        $user->update($updateData);

        if ($oldType !== $data['type']) {
            $user->roles()->detach();
            if ($data['type'] === 'Student Staff') {
                $user->assignRole('student-staff');
            } else {
                $user->assignRole('staff');
            }
        }

        return back()->with('status', 'Data staff berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return back()->with('status', 'Staff berhasil dihapus.');
    }
}
