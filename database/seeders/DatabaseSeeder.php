<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed legacy presence/overtime data (keeps historical records)
        $this->call(LegacyDataSeeder::class);

        // 2. Seed holidays
        $this->call(HolidaySeeder::class);

        // 3. Assign roles, permissions, and user credentials
        $this->call(RoleUserSeeder::class);
    }
}
