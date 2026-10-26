<?php

use App\Models\AuditLog;
use App\Models\CampusLocation;
use App\Models\ServiceCatalog;
use App\Models\Unit;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/** @var TestCase $this */
beforeEach(function () {
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
});

test('non-admin is forbidden from accessing master data and audit logs', function () {
    $staff = User::factory()->create(['is_active' => true]);
    $staff->assignRole('staff');

    $this->actingAs($staff)
        ->get('/admin/master-data')
        ->assertStatus(403);

    $this->actingAs($staff)
        ->get('/admin/audit-logs')
        ->assertStatus(403);
});

test('super admin can view master data page', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    Unit::create(['name' => 'Biro Akademik', 'code' => 'BAK']);
    CampusLocation::create(['name' => 'Gedung Rektorat', 'type' => 'building']);
    ServiceCatalog::create(['name' => 'Gangguan WiFi Kampus', 'category' => 'Network', 'sla_resolution_hours' => 4]);

    $this->actingAs($admin)
        ->get('/admin/master-data')
        ->assertStatus(200)
        ->assertSee('Data Master & SLA')
        ->assertSee('Biro Akademik')
        ->assertSee('Gedung Rektorat')
        ->assertSee('Gangguan WiFi Kampus');
});

test('super admin can store and delete unit', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $this->actingAs($admin)
        ->post('/admin/units', [
            'name' => 'Fakultas Teknik',
            'code' => 'FT-01',
        ])
        ->assertRedirect();

    $unit = Unit::where('code', 'FT-01')->first();
    expect($unit)->not->toBeNull()
        ->and($unit->name)->toBe('Fakultas Teknik');

    // Audit log should be recorded
    $log = AuditLog::where('action', 'unit.created')->where('model_id', $unit->id)->first();
    expect($log)->not->toBeNull();

    // Delete unit
    $this->actingAs($admin)
        ->delete("/admin/units/{$unit->id}")
        ->assertRedirect();

    expect(Unit::find($unit->id))->toBeNull();
});

test('super admin can store and delete campus location', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $this->actingAs($admin)
        ->post('/admin/locations', [
            'name' => 'Gedung A',
            'type' => 'building',
            'code' => 'G-A',
        ])
        ->assertRedirect();

    $loc = CampusLocation::where('name', 'Gedung A')->first();
    expect($loc)->not->toBeNull()
        ->and($loc->type)->toBe('building');

    $this->actingAs($admin)
        ->delete("/admin/locations/{$loc->id}")
        ->assertRedirect();

    expect(CampusLocation::find($loc->id))->toBeNull();
});

test('super admin can store and delete service catalog', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $this->actingAs($admin)
        ->post('/admin/services', [
            'category' => 'Hardware',
            'name' => 'Perbaikan Printer',
            'sla_resolution_hours' => 8,
        ])
        ->assertRedirect();

    $service = ServiceCatalog::where('name', 'Perbaikan Printer')->first();
    expect($service)->not->toBeNull()
        ->and($service->sla_resolution_hours)->toBe(8);

    $this->actingAs($admin)
        ->delete("/admin/services/{$service->id}")
        ->assertRedirect();

    expect(ServiceCatalog::find($service->id))->toBeNull();
});

test('super admin can view and search audit logs', function () {
    $admin = User::factory()->create(['name' => 'Super Admin Test', 'is_active' => true]);
    $admin->assignRole('super-admin');

    AuditLog::create([
        'user_id' => $admin->id,
        'action' => 'test.action.sample',
        'ip_address' => '127.0.0.1',
        'created_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get('/admin/audit-logs')
        ->assertStatus(200)
        ->assertSee('Audit Log Administratif')
        ->assertSee('test.action.sample');

    // Search filter
    $this->actingAs($admin)
        ->get('/admin/audit-logs?search=test.action.sample')
        ->assertStatus(200)
        ->assertSee('test.action.sample');
});
