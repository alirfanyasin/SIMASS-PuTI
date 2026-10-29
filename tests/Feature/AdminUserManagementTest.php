<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/** @var TestCase $this */
beforeEach(function () {
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'student-staff', 'guard_name' => 'web']);
});

test('super admin can view user management index', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $target = User::factory()->create(['name' => 'Budi Santoso', 'username' => 'budis']);

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertStatus(200)
        ->assertSee('Manajemen Akun Pengguna')
        ->assertSee('Budi Santoso');
});

test('super admin can create a user with role and unit', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $response = $this->actingAs($admin)->post('/admin/users', [
        'name' => 'Ahmad Teknisi',
        'username' => 'ahmadtek',
        'email' => 'ahmad@telkomuniversity.ac.id',
        'role' => 'staff',
        'type' => 'Staf',
        'position' => 'NOC Officer',
    ]);

    $response->assertRedirect();

    $created = User::where('username', 'ahmadtek')->first();
    expect($created)->not->toBeNull()
        ->and($created->is_active)->toBeTrue()
        ->and($created->hasRole('staff'))->toBeTrue();
});

test('super admin can toggle active status of user', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $target = User::factory()->create(['is_active' => true]);
    $target->assignRole('staff');

    $this->actingAs($admin)->patch("/admin/users/{$target->id}/toggle-status")
        ->assertRedirect();

    expect($target->fresh()->is_active)->toBeFalse();

    $this->actingAs($admin)->patch("/admin/users/{$target->id}/toggle-status")
        ->assertRedirect();

    expect($target->fresh()->is_active)->toBeTrue();
});

test('super admin can import users from csv', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $csvContent = "name,username,email,type,position,role\n";
    $csvContent .= "Imported User One,impuser1,imp1@telkomuniversity.ac.id,Student Staff,Support,student-staff\n";
    $csvContent .= "Imported User Two,impuser2,imp2@telkomuniversity.ac.id,Staf,Admin,staff\n";

    $file = UploadedFile::fake()->createWithContent('users.csv', $csvContent);

    $response = $this->actingAs($admin)->post('/admin/users/import-csv', [
        'file' => $file,
    ]);

    $response->assertRedirect();

    expect(User::where('username', 'impuser1')->exists())->toBeTrue()
        ->and(User::where('username', 'impuser2')->exists())->toBeTrue();
});

test('super admin can soft delete a user', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $target = User::factory()->create(['is_active' => true]);
    $target->assignRole('staff');

    $this->actingAs($admin)->delete("/admin/users/{$target->id}")
        ->assertRedirect();

    expect(User::find($target->id))->toBeNull()
        ->and(User::withTrashed()->find($target->id))->not->toBeNull();
});

test('username and email of a deleted user can be reused', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super-admin');

    $target = User::factory()->create(['username' => 'reynanda', 'email' => 'rey@telkomuniversity.ac.id']);
    $target->assignRole('staff');

    $this->actingAs($admin)->delete("/admin/users/{$target->id}");

    $this->actingAs($admin)->post('/admin/users', [
        'name' => 'Reynanda',
        'username' => 'reynanda',
        'email' => 'rey@telkomuniversity.ac.id',
        'role' => 'staff',
    ])->assertSessionHasNoErrors();

    expect(User::where('username', 'reynanda')->exists())->toBeTrue();
});
