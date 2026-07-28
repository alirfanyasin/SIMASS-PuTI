<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            ['date' => '2026-01-01', 'name' => 'Tahun Baru 2026 Masehi', 'is_national' => true],
            ['date' => '2026-02-17', 'name' => 'Isra Mikraj Nabi Muhammad SAW', 'is_national' => true],
            ['date' => '2026-02-19', 'name' => 'Tahun Baru Imlek 2577 Kongzili', 'is_national' => true],
            ['date' => '2026-03-22', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1948', 'is_national' => true],
            ['date' => '2026-03-20', 'name' => 'Idul Fitri 1447 Hijriah', 'is_national' => true],
            ['date' => '2026-03-21', 'name' => 'Idul Fitri 1447 Hijriah', 'is_national' => true],
            ['date' => '2026-04-03', 'name' => 'Wafat Isa Al Masih', 'is_national' => true],
            ['date' => '2026-05-01', 'name' => 'Hari Buruh Internasional', 'is_national' => true],
            ['date' => '2026-05-14', 'name' => 'Kenaikan Isa Al Masih', 'is_national' => true],
            ['date' => '2026-05-27', 'name' => 'Idul Adha 1447 Hijriah', 'is_national' => true],
            ['date' => '2026-05-31', 'name' => 'Hari Raya Waisak 2570 BE', 'is_national' => true],
            ['date' => '2026-06-01', 'name' => 'Hari Lahir Pancasila', 'is_national' => true],
            ['date' => '2026-06-17', 'name' => 'Tahun Baru Islam 1448 Hijriah', 'is_national' => true],
            ['date' => '2026-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia', 'is_national' => true],
            ['date' => '2026-08-25', 'name' => 'Maulid Nabi Muhammad SAW', 'is_national' => true],
            ['date' => '2026-12-25', 'name' => 'Hari Raya Natal', 'is_national' => true],
        ];

        foreach ($holidays as $h) {
            \App\Models\Holiday::updateOrCreate(['date' => $h['date']], $h);
        }

        $this->command->info("Holidays seeded for 2026 manually.");
    }
}
