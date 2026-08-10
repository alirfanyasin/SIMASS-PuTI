<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------
        // 1. Create Permissions
        // -------------------------------------------------------
        $permissions = [
            // Presence
            'view-presence',
            'create-presence',
            'manage-presence',
            'view-presence-history',
            'manage-overtime',

            // Holiday
            'view-holiday',
            'manage-holiday',

            // Settings
            'view-settings',
            'manage-settings',

            // Roles
            'view-roles',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // -------------------------------------------------------
        // 2. Create Roles and Assign Permissions
        // -------------------------------------------------------

        /** @var Role $superAdmin */
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $allPermissions = Permission::where('name', '!=', 'create-presence')->get();
        $superAdmin->syncPermissions($allPermissions);

        /** @var Role $studentStaff */
        $studentStaff = Role::firstOrCreate(['name' => 'student-staff', 'guard_name' => 'web']);
        $studentStaff->syncPermissions([
            'view-presence',
            'create-presence',
            'view-presence-history',
            'manage-overtime',
            'view-holiday',
        ]);

        /** @var Role $staff */
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'view-presence',
            'manage-presence',
            'view-presence-history',
            'manage-overtime',
            'view-holiday',
            'manage-holiday',
            'view-settings',
        ]);

        /** @var Role $user */
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->syncPermissions([
            // user role has no specific permissions for presence or holiday, only ticketing (which is public to all authenticated users)
        ]);

        // -------------------------------------------------------
        // 3. Seed Users with Emails + Passwords + Roles
        // -------------------------------------------------------

        /**
         * Users from LegacyDataSeeder:
         * id=1  Irfan Yasin            (Student Staff)
         * id=2  Amoure Chelsytrivia    (Student Staff)
         * id=3  Reza Eka Firmansyah    (Student Staff)
         * id=4  Reynanda Shaquille     (Staf)
         * id=5  Rizal Jihadi           (Staf)
         * id=6  Selfina Anggraini      (Staf)
         * id=7  Rahadian A. Setyawan   (Staf)
         * id=9  Cahyo Priyo Purnomo    (Staf)
         * id=10 Fitriani Latifah       (Student Staff)
         *
         * Additional super-admin: putisby (new user)
         */
        $usersData = [
            // super-admin (new — not in legacy)
            [
                'name' => 'Putis BY',
                'username' => 'putisby',
                'email' => 'putisby@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Staf',
                'position' => 'IT Manager',
                'role' => 'super-admin',
            ],

            // student-staff (map to existing legacy IDs)
            [
                'id' => 1,
                'name' => 'Irfan Yasin',
                'username' => 'irfanyasin',
                'email' => 'irfanyasin@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Student Staff',
                'position' => 'Wordpress Developer',
                'role' => 'student-staff',
            ],
            [
                'id' => 2,
                'name' => 'Amoure Chelsytrivia Daniella Purba',
                'username' => 'amourepurba',
                'email' => 'amourepurba@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Student Staff',
                'position' => 'IT Support',
                'role' => 'student-staff',
            ],
            [
                'id' => 3,
                'name' => 'Reza Eka Firmansyah',
                'username' => 'rezafirmansyah',
                'email' => 'rezafirmansyah@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Student Staff',
                'position' => 'Network Engineer',
                'role' => 'student-staff',
            ],
            [
                'id' => 10,
                'name' => 'Fitriani Latifah',
                'username' => 'fitrianilatifa',
                'email' => 'fitrianilatifa@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Student Staff',
                'position' => 'Designer',
                'role' => 'student-staff',
            ],

            // staff (map to existing legacy IDs)
            [
                'id' => 4,
                'name' => 'Reynanda Shaquille Purwanto',
                'username' => 'reynandashaquille',
                'email' => 'reynandashaquille@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Staf',
                'position' => 'Network Engineer',
                'role' => 'staff',
            ],
            [
                'id' => 5,
                'name' => 'Rizal Jihadi',
                'username' => 'rizaljihadi',
                'email' => 'rizaljihadi@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Staf',
                'position' => 'Software Developer',
                'role' => 'staff',
            ],
            [
                'id' => 6,
                'name' => 'Selfina Anggraini',
                'username' => 'selfinaanggraini',
                'email' => 'selfinaanggraini@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Staf',
                'position' => 'IT Manager',
                'role' => 'staff',
            ],
            [
                'id' => 7,
                'name' => 'Rahadian A. Setyawan',
                'username' => 'rahadiansetyawan',
                'email' => 'rahadiansetyawan@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Staf',
                'position' => 'Network Engineer',
                'role' => 'staff',
            ],
            [
                'id' => 9,
                'name' => 'PuTI',
                'username' => 'putisby',
                'email' => 'putisby@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'type' => 'Staf',
                'position' => 'Network Engineer',
                'role' => 'super-admin',
            ],
        ];

        $roleMap = [
            'super-admin' => $superAdmin,
            'student-staff' => $studentStaff,
            'staff' => $staff,
            'user' => $user,
        ];

        User::unguard();

        foreach ($usersData as $data) {
            $role = $roleMap[$data['role']];

            $u = null;
            if (isset($data['id'])) {
                $u = User::find($data['id']);
            }
            if (! $u) {
                $u = User::where('email', $data['email'])->first();
            }

            if ($u) {
                $u->update([
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'type' => $data['type'] ?? null,
                    'position' => $data['position'] ?? null,
                ]);
            } else {
                $insertData = [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'type' => $data['type'] ?? null,
                    'position' => $data['position'] ?? null,
                ];
                if (isset($data['id'])) {
                    $insertData['id'] = $data['id'];
                }
                $u = User::create($insertData);
            }

            $u->syncRoles([$role]);
        }

        User::reguard();

        $this->command->info('✅ Roles, permissions, and users seeded successfully.');
        $this->command->table(
            ['Username', 'Email', 'Role'],
            collect($usersData)->map(fn ($u) => [$u['username'], $u['email'], $u['role']])->toArray()
        );
    }
}
