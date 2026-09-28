<?php

use App\Models\Overtime;
use App\Models\Presence;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

test('automatically checks out at 16:30 and records early overtime', function () {
    Carbon::setTestNow('2026-09-28 16:31:00');
    $user = User::factory()->create();
    $presence = Presence::create([
        'user_id' => $user->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '08:00:00',
        'hari' => 'Senin',
    ]);

    $this->artisan('presence:auto-checkout')->assertSuccessful();

    expect($presence->fresh())
        ->jam_pulang->toBe('16:30:00')
        ->total_jam->toBe('8 Jam 30 Menit')
        ->menit_tambahan->toBe(30);

    expect(Overtime::where('presence_id', $presence->id)->first())
        ->durasi_menit->toBe(30)
        ->sisa_menit->toBe(30);
});

test('does not check out before 16:30', function () {
    Carbon::setTestNow('2026-09-28 16:29:00');
    $user = User::factory()->create();
    $presence = Presence::create([
        'user_id' => $user->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '08:30:00',
        'hari' => 'Senin',
    ]);

    $this->artisan('presence:auto-checkout')->assertSuccessful();

    expect($presence->fresh()->jam_pulang)->toBeNull();
});

test('uses the configured checkout time', function () {
    Setting::create(['key' => 'jam_masuk', 'value' => '09:00']);
    Setting::create(['key' => 'jam_pulang', 'value' => '17:00']);
    Carbon::setTestNow('2026-09-28 17:01:00');
    $user = User::factory()->create();
    $presence = Presence::create([
        'user_id' => $user->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '08:30:00',
        'hari' => 'Senin',
    ]);

    $this->artisan('presence:auto-checkout')->assertSuccessful();

    expect($presence->fresh()->jam_pulang)->toBe('17:00:00')
        ->and(Overtime::where('presence_id', $presence->id)->value('durasi_menit'))->toBe(30);
});

test('presence list and PDF show the same rounded duration while database stays exact', function () {
    $user = User::factory()->create();
    Role::create(['name' => 'student-staff']);
    $user->assignRole('student-staff');
    $presence = Presence::create([
        'user_id' => $user->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '08:30:00',
        'jam_pulang' => '14:00:00',
        'hari' => 'Senin',
    ]);
    $presence->recalculateTotalJam();

    $this->actingAs($user)
        ->get('/presence/list?start_date=2026-09-28&end_date=2026-09-28')
        ->assertSuccessful()
        ->assertSee('6 Jam');

    $this->actingAs($user)
        ->get('/export-pdf?filterNama='.$user->id.'&startDate=2026-09-28&endDate=2026-09-28')
        ->assertSuccessful()
        ->assertSee('6 Jam');

    expect($presence->fresh()->total_jam)->toBe('5 Jam 30 Menit');
});
