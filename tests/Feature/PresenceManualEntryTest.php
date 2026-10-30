<?php

use App\Models\Presence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'student-staff', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'manage-presence', 'guard_name' => 'web']);

    $this->admin = User::factory()->create();
    $this->admin->givePermissionTo('manage-presence');

    $this->staff = User::factory()->create();
    $this->staff->assignRole('student-staff');
});

test('admin can add a manual presence for student staff', function () {
    $this->actingAs($this->admin)->post('/presence/manual', [
        'user_id' => $this->staff->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '08:00',
        'jam_pulang' => '12:30',
        'pekerjaan' => 'Lupa check-in',
    ])->assertSessionHasNoErrors();

    $presence = Presence::where('user_id', $this->staff->id)->first();
    expect($presence->hari)->toBe('Senin')
        ->and($presence->total_jam)->toBe('4 Jam 30 Menit');
});

test('manual presence rejects duplicate date', function () {
    $payload = ['user_id' => $this->staff->id, 'tanggal' => '2026-09-28', 'jam_masuk' => '08:00'];

    $this->actingAs($this->admin)->post('/presence/manual', $payload);
    $this->actingAs($this->admin)->post('/presence/manual', $payload)->assertSessionHasErrors('tanggal');

    expect(Presence::count())->toBe(1);
});

test('presence list on the 31st stays in the current cycle', function () {
    $this->actingAs($this->admin)->post('/presence/manual', [
        'user_id' => $this->staff->id,
        'tanggal' => '2026-10-28',
        'jam_masuk' => '08:00',
    ]);

    // addMonth() on Oct 31 overflowed to Dec 1, jumping to the December cycle.
    $this->travelTo('2026-10-31 10:00:00');

    $this->actingAs($this->admin)->get('/presence/list')
        ->assertOk()
        ->assertViewHas('startDate', '2026-10-16')
        ->assertViewHas('presensiData', fn ($rows) => count($rows) === 1);
});

test('user without manage-presence cannot add manual presence', function () {
    $this->actingAs($this->staff)->post('/presence/manual', [
        'user_id' => $this->staff->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '08:00',
    ])->assertForbidden();
});
