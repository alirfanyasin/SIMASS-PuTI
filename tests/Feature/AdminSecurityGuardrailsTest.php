<?php

use App\Models\AuditLog;
use App\Models\PortalModule;
use App\Models\User;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/** @var TestCase $this */
beforeEach(function () {
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
});

test('guest is redirected away from admin dashboard', function () {
    $this->get('/admin/dashboard')
        ->assertRedirect('/');
});

test('non-admin user receives 403 on admin dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('staff');

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertStatus(403);
});

test('inactive super-admin receives 403 on admin dashboard', function () {
    $admin = User::factory()->create(['is_active' => false]);
    $admin->assignRole('super-admin');

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertStatus(403);
});

test('active super-admin can access admin dashboard', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertStatus(200)
        ->assertSee('Admin Console')
        ->assertSee('Total Pengguna');
});

test('user policy prevents self lockout and last super-admin destruction', function () {
    $policy = new UserPolicy;

    $admin1 = User::factory()->create(['is_active' => true]);
    $admin1->assignRole('super-admin');

    // Self actions blocked
    expect($policy->deactivate($admin1, $admin1))->toBeFalse()
        ->and($policy->delete($admin1, $admin1))->toBeFalse()
        ->and($policy->demote($admin1, $admin1))->toBeFalse();

    // Sole super admin target blocked
    $staff = User::factory()->create(['is_active' => true]);
    $staff->assignRole('staff');
    expect($policy->deactivate($staff, $admin1))->toBeFalse()
        ->and($policy->delete($staff, $admin1))->toBeFalse();

    // Adding second super admin allows managing admin1
    $admin2 = User::factory()->create(['is_active' => true]);
    $admin2->assignRole('super-admin');
    expect($policy->deactivate($admin2, $admin1))->toBeTrue()
        ->and($policy->delete($admin2, $admin1))->toBeTrue()
        ->and($policy->demote($admin2, $admin1))->toBeTrue();
});

test('auditable trait records model changes', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $this->actingAs($admin);

    $module = PortalModule::create([
        'code' => 'test-mod',
        'name' => 'Initial Module',
        'order' => 1,
    ]);

    expect(AuditLog::where('model_type', PortalModule::class)->where('action', 'created')->exists())->toBeTrue();

    $module->update(['name' => 'Renamed Module']);
    $updateLog = AuditLog::where('model_type', PortalModule::class)->where('action', 'updated')->latest('id')->first();

    expect($updateLog->old_values['name'])->toBe('Initial Module')
        ->and($updateLog->new_values['name'])->toBe('Renamed Module');
});
