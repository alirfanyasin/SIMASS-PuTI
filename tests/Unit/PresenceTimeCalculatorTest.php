<?php

use App\Models\Presence;
use App\Services\PresenceTimeCalculator;
use Illuminate\Support\Collection;

test('rounds thirty minutes up and twenty nine minutes down', function () {
    $calculator = new PresenceTimeCalculator('08:30', '16:30');

    expect($calculator->roundMinutesToHours(330))->toBe(6)
        ->and($calculator->roundMinutesToHours(329))->toBe(5);
});

test('caps regular time at eight hours and at the configured checkout time', function () {
    $calculator = new PresenceTimeCalculator('08:30', '16:30');
    $presence = new Presence([
        'tanggal' => '2026-09-28',
        'jam_masuk' => '08:00:00',
        'jam_pulang' => '17:00:00',
    ]);
    $presence->setRelation('overtimeTransfers', new Collection);

    expect($calculator->regularMinutes($presence))->toBe(480)
        ->and($calculator->roundedDisplayHours($presence))->toBe(8);
});

test('turns time before 08:30 into overtime', function () {
    $calculator = new PresenceTimeCalculator('08:30', '16:30');
    $presence = new Presence([
        'tanggal' => '2026-09-28',
        'jam_masuk' => '07:45:00',
        'jam_pulang' => '16:30:00',
    ]);

    expect($calculator->earlyOvertimeMinutes($presence))->toBe(45);
});

test('does not create overtime when an early arrival works no more than eight hours', function () {
    $calculator = new PresenceTimeCalculator('08:30', '16:30');
    $presence = new Presence([
        'tanggal' => '2026-09-28',
        'jam_masuk' => '07:45:00',
        'jam_pulang' => '15:45:00',
    ]);

    expect($calculator->earlyOvertimeMinutes($presence))->toBe(0)
        ->and($calculator->regularMinutes($presence))->toBe(480);
});
