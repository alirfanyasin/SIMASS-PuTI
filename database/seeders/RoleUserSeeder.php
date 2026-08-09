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
                'username' => 'irfanyasin',
                'email' => 'irfanyasin@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'student-staff',
            ],
            [
                'id' => 2,
                'username' => 'amourepurba',
                'email' => 'amourepurba@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'student-staff',
            ],
            [
                'id' => 3,
                'username' => 'rezafirmansyah',
                'email' => 'rezafirmansyah@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'student-staff',
            ],
            [
                'id' => 10,
                'username' => 'fitrianilatifa',
                'email' => 'fitrianilatifa@student.telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'student-staff',
            ],

            // staff (map to existing legacy IDs)
            [
                'id' => 4,
                'username' => 'reynandashaquille',
                'email' => 'reynandashaquille@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
            [
                'id' => 5,
                'username' => 'rizaljihadi',
                'email' => 'rizaljihadi@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
            [
                'id' => 6,
                'username' => 'selfinaanggraini',
                'email' => 'selfinaanggraini@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
            [
                'id' => 7,
                'username' => 'rahadiansetyawan',
                'email' => 'rahadiansetyawan@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
            [
                'id' => 9,
                'username' => 'cahyopriyo',
                'email' => 'cahyopriyo@telkomuniversity.ac.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
        ];

        $roleMap = [
            'super-admin' => $superAdmin,
            'student-staff' => $studentStaff,
            'staff' => $staff,
            'user' => $user,
        ];

        foreach ($usersData as $data) {
            $role = $roleMap[$data['role']];

            if (isset($data['id'])) {
                // Update existing legacy user
                $u = User::find($data['id']);

                if ($u) {
                    $u->update([
                        'username' => $data['username'],
                        'email' => $data['email'],
                        'password' => $data['password'],
                    ]);
                    $u->syncRoles([$role]);
                }
            } else {
                // Create new user
                $u = User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'username' => $data['username'],
                        'email' => $data['email'],
                        'password' => $data['password'],
                        'type' => $data['type'] ?? null,
                        'position' => $data['position'] ?? null,
                    ]
                );
                $u->syncRoles([$role]);
            }
        }

        $this->command->info('✅ Roles, permissions, and users seeded successfully.');
        $this->command->table(
            ['Username', 'Email', 'Role'],
            collect($usersData)->map(fn ($u) => [$u['username'], $u['email'], $u['role']])->toArray()
        );
    }
}
