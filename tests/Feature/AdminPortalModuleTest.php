<?php

use App\Models\PortalModule;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/** @var TestCase $this */
beforeEach(function () {
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'student-staff', 'guard_name' => 'web']);
});

test('non-admin user cannot access portal module management', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('staff');

    $this->actingAs($user)
        ->get('/admin/modules')
        ->assertStatus(403);
});

test('super admin can access portal module management index', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    PortalModule::create([
        'code' => 'test-app',
        'name' => 'Aplikasi Uji Coba',
        'description' => 'Deskripsi modul uji coba',
        'credit' => 'Courtesy to Tester',
        'icon' => 'box',
        'status' => 'active',
        'order' => 1,
    ]);

    $this->actingAs($admin)
        ->get('/admin/modules')
        ->assertStatus(200)
        ->assertSee('Manajemen Kartu Modul Portal')
        ->assertSee('Aplikasi Uji Coba')
        ->assertSee('Courtesy to Tester');
});

test('super admin can create a new portal module', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $response = $this->actingAs($admin)->post('/admin/modules', [
        'name' => 'Modul Penjaminan Mutu',
        'code' => 'mutu',
        'description' => 'Sistem monitoring SPMI Telkom University Surabaya.',
        'credit' => 'Courtesy to Tim SPMI',
        'icon' => 'award',
        'status' => 'coming_soon',
        'order' => 7,
        'route_name' => 'mutu.dashboard',
        'allowed_roles' => ['staff', 'super-admin'],
    ]);

    $response->assertRedirect();

    $module = PortalModule::where('code', 'mutu')->first();
    expect($module)->not->toBeNull()
        ->and($module->name)->toBe('Modul Penjaminan Mutu')
        ->and($module->credit)->toBe('Courtesy to Tim SPMI')
        ->and($module->status)->toBe('coming_soon')
        ->and($module->order)->toBe(7)
        ->and($module->allowed_roles)->toBe(['staff', 'super-admin']);
});

test('super admin can update an existing portal module', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $module = PortalModule::create([
        'code' => 'eticket',
        'name' => 'e-Ticket PuTI',
        'description' => 'Deskripsi lama',
        'credit' => 'Old Credit',
        'icon' => 'ticket',
        'status' => 'active',
        'order' => 3,
    ]);

    $response = $this->actingAs($admin)->put("/admin/modules/{$module->id}", [
        'name' => 'e-Ticket PuTI V2',
        'code' => 'eticket',
        'description' => 'Deskripsi baru yang diperbarui',
        'credit' => 'Courtesy to Reynanda Shaquile S.Kom',
        'icon' => 'life-buoy',
        'status' => 'active',
        'order' => 2,
        'route_name' => 'ticket.index',
    ]);

    $response->assertRedirect();

    $updated = $module->fresh();
    expect($updated->name)->toBe('e-Ticket PuTI V2')
        ->and($updated->description)->toBe('Deskripsi baru yang diperbarui')
        ->and($updated->credit)->toBe('Courtesy to Reynanda Shaquile S.Kom')
        ->and($updated->icon)->toBe('life-buoy')
        ->and($updated->order)->toBe(2);
});

test('super admin can create a module with outer link', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $response = $this->actingAs($admin)->post('/admin/modules', [
        'name' => 'Telegram Bot PuTI',
        'code' => 'tg-bot',
        'description' => 'Bot interaktif telegram.',
        'credit' => 'Courtesy to Dev Bot',
        'icon' => 'bot',
        'status' => 'active',
        'order' => 8,
        'link_type' => 'url',
        'url' => 'https://t.me/puti_telkom_bot',
    ]);

    $response->assertRedirect();

    $module = PortalModule::where('code', 'tg-bot')->first();
    expect($module)->not->toBeNull()
        ->and($module->url)->toBe('https://t.me/puti_telkom_bot')
        ->and($module->route_name)->toBeNull()
        ->and($module->isExternalLink())->toBeTrue()
        ->and($module->getTargetUrl())->toBe('https://t.me/puti_telkom_bot');
});

test('super admin can delete a portal module', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $module = PortalModule::create([
        'code' => 'deprecated-mod',
        'name' => 'Modul Lama',
        'icon' => 'trash',
        'status' => 'maintenance',
        'order' => 99,
    ]);

    $response = $this->actingAs($admin)->delete("/admin/modules/{$module->id}");
    $response->assertRedirect();

    expect(PortalModule::where('code', 'deprecated-mod')->exists())->toBeFalse();
});
