<?php

use App\Models\Presence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('checkout time cannot be earlier than checkin time when editing presence', function () {
    $user = User::factory()->create();
    $presence = Presence::create([
        'user_id' => $user->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '14:45:00',
        'jam_pulang' => '16:30:00',
        'hari' => 'Senin',
    ]);

    $this->actingAs($user)
        ->put('/presence/'.$presence->id, [
            'jam_masuk' => '14:45',
            'jam_pulang' => '11:30',
            'pekerjaan' => 'Pekerjaan hari ini',
        ])
        ->assertSessionHasErrors([
            'jam_pulang' => 'Jam pulang tidak boleh lebih kecil dari jam masuk.',
        ]);

    expect($presence->fresh()->jam_pulang)->toBe('16:30:00');
});

test('checkout time equal to or later than checkin time is accepted', function () {
    $user = User::factory()->create();
    $presence = Presence::create([
        'user_id' => $user->id,
        'tanggal' => '2026-09-28',
        'jam_masuk' => '14:45:00',
        'jam_pulang' => null,
        'hari' => 'Senin',
    ]);

    $this->actingAs($user)
        ->put('/presence/'.$presence->id, [
            'jam_masuk' => '14:45',
            'jam_pulang' => '15:30',
            'pekerjaan' => 'Pekerjaan hari ini',
        ])
        ->assertSessionHasNoErrors();

    expect($presence->fresh()->jam_pulang)->toBe('15:30');
});
