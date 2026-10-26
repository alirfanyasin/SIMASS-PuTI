<?php

use App\Models\AuditLog;
use App\Models\CampusLocation;
use App\Models\PortalModule;
use App\Models\ServiceCatalog;
use App\Models\Unit;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/** @var TestCase $this */
test('user supports soft delete, active state, and unit assignment', function () {
    $unit = Unit::create(['name' => 'Direktorat PuTI', 'code' => 'PUTI']);
    $user = User::factory()->create([
        'unit_id' => $unit->id,
        'is_active' => true,
    ]);

    expect($user->unit->name)->toBe('Direktorat PuTI')
        ->and($user->is_active)->toBeTrue();

    $user->delete();
    expect(User::find($user->id))->toBeNull()
        ->and(User::withTrashed()->find($user->id))->not->toBeNull();
});

test('campus locations support hierarchy', function () {
    $building = CampusLocation::create(['type' => 'building', 'name' => 'Gedung Panambulai', 'code' => 'PA']);
    $floor = CampusLocation::create(['parent_id' => $building->id, 'type' => 'floor', 'name' => 'Lantai 1']);

    expect($floor->parent->id)->toBe($building->id)
        ->and($building->children)->toHaveCount(1);
});

test('portal module evaluates role accessibility and ordered scope', function () {
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

    $adminModule = PortalModule::create([
        'code' => 'admin',
        'name' => 'Admin Console',
        'order' => 0,
        'allowed_roles' => ['super-admin'],
    ]);

    $publicModule = PortalModule::create([
        'code' => 'presensi',
        'name' => 'SIMASS Presensi',
        'order' => 1,
        'allowed_roles' => null,
    ]);

    $admin = User::factory()->create();
    $admin->assignRole('super-admin');

    $staff = User::factory()->create();
    $staff->assignRole('staff');

    expect($adminModule->isAccessibleBy($admin))->toBeTrue()
        ->and($adminModule->isAccessibleBy($staff))->toBeFalse()
        ->and($publicModule->isAccessibleBy($staff))->toBeTrue()
        ->and(PortalModule::ordered()->first()->code)->toBe('admin');
});

test('service catalog and audit log record correctly', function () {
    $catalog = ServiceCatalog::create([
        'category' => 'Jaringan',
        'name' => 'Koneksi Wi-Fi',
        'sla_resolution_hours' => 4,
    ]);

    $log = AuditLog::create([
        'action' => 'created',
        'model_type' => ServiceCatalog::class,
        'model_id' => $catalog->id,
        'new_values' => ['name' => 'Koneksi Wi-Fi'],
    ]);

    expect($catalog->sla_resolution_hours)->toBe(4)
        ->and($log->new_values['name'])->toBe('Koneksi Wi-Fi');
});
