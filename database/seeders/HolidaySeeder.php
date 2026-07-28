<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data hari libur nasional Indonesia 2026-2033 (hardcoded karena API tidak tersedia).
     */
    public function run(): void
    {
        $holidays = [
            // ===================== 2026 =====================
            ['date' => '2026-01-01', 'name' => 'Tahun Baru 2026 Masehi'],
            ['date' => '2026-02-17', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2026-02-19', 'name' => 'Tahun Baru Imlek 2577 Kongzili'],
            ['date' => '2026-03-20', 'name' => 'Idul Fitri 1447 Hijriah'],
            ['date' => '2026-03-21', 'name' => 'Idul Fitri 1447 Hijriah'],
            ['date' => '2026-03-22', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1948'],
            ['date' => '2026-04-03', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2026-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2026-05-14', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2026-05-27', 'name' => 'Idul Adha 1447 Hijriah'],
            ['date' => '2026-05-31', 'name' => 'Hari Raya Waisak 2570 BE'],
            ['date' => '2026-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2026-06-17', 'name' => 'Tahun Baru Islam 1448 Hijriah'],
            ['date' => '2026-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2026-08-25', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2026-12-25', 'name' => 'Hari Raya Natal'],

            // ===================== 2027 =====================
            ['date' => '2027-01-01', 'name' => 'Tahun Baru 2027 Masehi'],
            ['date' => '2027-02-08', 'name' => 'Tahun Baru Imlek 2578 Kongzili'],
            ['date' => '2027-03-10', 'name' => 'Idul Fitri 1448 Hijriah'],
            ['date' => '2027-03-11', 'name' => 'Idul Fitri 1448 Hijriah'],
            ['date' => '2027-03-12', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1949'],
            ['date' => '2027-04-02', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2027-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2027-05-07', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2027-05-13', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2027-05-17', 'name' => 'Idul Adha 1448 Hijriah'],
            ['date' => '2027-05-20', 'name' => 'Hari Raya Waisak 2571 BE'],
            ['date' => '2027-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2027-06-07', 'name' => 'Tahun Baru Islam 1449 Hijriah'],
            ['date' => '2027-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2027-08-15', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2027-12-25', 'name' => 'Hari Raya Natal'],

            // ===================== 2028 =====================
            ['date' => '2028-01-01', 'name' => 'Tahun Baru 2028 Masehi'],
            ['date' => '2028-01-27', 'name' => 'Tahun Baru Imlek 2579 Kongzili'],
            ['date' => '2028-02-26', 'name' => 'Idul Fitri 1449 Hijriah'],
            ['date' => '2028-02-27', 'name' => 'Idul Fitri 1449 Hijriah'],
            ['date' => '2028-03-01', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1950'],
            ['date' => '2028-04-14', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2028-04-25', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2028-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2028-05-05', 'name' => 'Idul Adha 1449 Hijriah'],
            ['date' => '2028-05-08', 'name' => 'Hari Raya Waisak 2572 BE'],
            ['date' => '2028-05-25', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2028-05-26', 'name' => 'Tahun Baru Islam 1450 Hijriah'],
            ['date' => '2028-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2028-08-04', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2028-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2028-12-25', 'name' => 'Hari Raya Natal'],

            // ===================== 2029 =====================
            ['date' => '2029-01-01', 'name' => 'Tahun Baru 2029 Masehi'],
            ['date' => '2029-01-14', 'name' => 'Idul Fitri 1450 Hijriah'],
            ['date' => '2029-01-15', 'name' => 'Idul Fitri 1450 Hijriah'],
            ['date' => '2029-02-15', 'name' => 'Tahun Baru Imlek 2580 Kongzili'],
            ['date' => '2029-03-16', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1951'],
            ['date' => '2029-04-14', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2029-04-30', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2029-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2029-05-15', 'name' => 'Tahun Baru Islam 1451 Hijriah'],
            ['date' => '2029-05-17', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2029-05-24', 'name' => 'Idul Adha 1450 Hijriah'],
            ['date' => '2029-05-27', 'name' => 'Hari Raya Waisak 2573 BE'],
            ['date' => '2029-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2029-07-25', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2029-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2029-12-25', 'name' => 'Hari Raya Natal'],

            // ===================== 2030 =====================
            ['date' => '2030-01-01', 'name' => 'Tahun Baru 2030 Masehi'],
            ['date' => '2030-01-03', 'name' => 'Idul Fitri 1451 Hijriah'],
            ['date' => '2030-01-04', 'name' => 'Idul Fitri 1451 Hijriah'],
            ['date' => '2030-02-04', 'name' => 'Tahun Baru Imlek 2581 Kongzili'],
            ['date' => '2030-03-04', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2030-03-05', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1952'],
            ['date' => '2030-04-19', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2030-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2030-05-05', 'name' => 'Tahun Baru Islam 1452 Hijriah'],
            ['date' => '2030-05-14', 'name' => 'Idul Adha 1451 Hijriah'],
            ['date' => '2030-05-16', 'name' => 'Hari Raya Waisak 2574 BE'],
            ['date' => '2030-05-30', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2030-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2030-07-14', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2030-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2030-12-25', 'name' => 'Hari Raya Natal'],

            // ===================== 2031 =====================
            ['date' => '2031-01-01', 'name' => 'Tahun Baru 2031 Masehi'],
            ['date' => '2031-01-22', 'name' => 'Tahun Baru Imlek 2582 Kongzili'],
            ['date' => '2031-02-20', 'name' => 'Idul Fitri 1452 Hijriah'],
            ['date' => '2031-02-21', 'name' => 'Idul Fitri 1452 Hijriah'],
            ['date' => '2031-02-22', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2031-03-25', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1953'],
            ['date' => '2031-04-11', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2031-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2031-05-03', 'name' => 'Idul Adha 1452 Hijriah'],
            ['date' => '2031-05-06', 'name' => 'Hari Raya Waisak 2575 BE'],
            ['date' => '2031-05-22', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2031-05-24', 'name' => 'Tahun Baru Islam 1453 Hijriah'],
            ['date' => '2031-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2031-07-03', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2031-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2031-12-25', 'name' => 'Hari Raya Natal'],

            // ===================== 2032 =====================
            ['date' => '2032-01-01', 'name' => 'Tahun Baru 2032 Masehi'],
            ['date' => '2032-02-10', 'name' => 'Idul Fitri 1453 Hijriah'],
            ['date' => '2032-02-11', 'name' => 'Idul Fitri 1453 Hijriah'],
            ['date' => '2032-02-11', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2032-02-12', 'name' => 'Tahun Baru Imlek 2583 Kongzili'],
            ['date' => '2032-03-13', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1954'],
            ['date' => '2032-04-21', 'name' => 'Idul Adha 1453 Hijriah'],
            ['date' => '2032-04-30', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2032-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2032-05-12', 'name' => 'Tahun Baru Islam 1454 Hijriah'],
            ['date' => '2032-05-13', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2032-05-25', 'name' => 'Hari Raya Waisak 2576 BE'],
            ['date' => '2032-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2032-06-21', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2032-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2032-12-25', 'name' => 'Hari Raya Natal'],

            // ===================== 2033 =====================
            ['date' => '2033-01-01', 'name' => 'Tahun Baru 2033 Masehi'],
            ['date' => '2033-01-30', 'name' => 'Idul Fitri 1454 Hijriah'],
            ['date' => '2033-01-31', 'name' => 'Idul Fitri 1454 Hijriah'],
            ['date' => '2033-02-01', 'name' => 'Tahun Baru Imlek 2584 Kongzili'],
            ['date' => '2033-02-01', 'name' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['date' => '2033-03-02', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1955'],
            ['date' => '2033-04-11', 'name' => 'Idul Adha 1454 Hijriah'],
            ['date' => '2033-04-15', 'name' => 'Wafat Isa Al Masih'],
            ['date' => '2033-05-01', 'name' => 'Hari Buruh Internasional'],
            ['date' => '2033-05-02', 'name' => 'Tahun Baru Islam 1455 Hijriah'],
            ['date' => '2033-05-05', 'name' => 'Kenaikan Isa Al Masih'],
            ['date' => '2033-05-14', 'name' => 'Hari Raya Waisak 2577 BE'],
            ['date' => '2033-06-01', 'name' => 'Hari Lahir Pancasila'],
            ['date' => '2033-06-11', 'name' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2033-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2033-12-25', 'name' => 'Hari Raya Natal'],
        ];

        $count = 0;
        foreach ($holidays as $h) {
            Holiday::updateOrCreate(
                ['date' => $h['date']],
                [
                    'name' => $h['name'],
                    'is_national' => true,
                ]
            );
            $count++;
        }

        $this->command->info("Seeded {$count} national holidays for years 2026–2033.");
    }
}
