<?php

use App\Models\User;

test('root redirects to login page', function () {
    $this->get('/')
        ->assertRedirect('/login');
});

test('login page is accessible to guests', function () {
    $this->get('/login')
        ->assertStatus(200)
        ->assertSee('Username SSO');
});

test('unauthenticated user is redirected to login from protected routes', function () {
    $this->get('/presence/dashboard')
        ->assertRedirect('/login');
});

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/presence/dashboard')
        ->assertStatus(200);
});

test('guest cannot access presence routes', function () {
    $this->get('/presence/')
        ->assertRedirect('/login');

    $this->get('/presence/list')
        ->assertRedirect('/login');

    $this->get('/presence/overtime')
        ->assertRedirect('/login');
});
