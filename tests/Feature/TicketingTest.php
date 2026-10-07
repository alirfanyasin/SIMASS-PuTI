<?php

use App\Models\Presence;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketAssignmentService;
use Tests\TestCase;

/** @var TestCase $this */
test('guest cannot access ticketing routes', function () {
    $this->get('/ticket')->assertRedirect('/');
    $this->get('/ticket/create')->assertRedirect('/');
    $this->get('/ticket/my-tickets')->assertRedirect('/');
});

test('authenticated user can view ticketing index and see categories', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/ticket')
        ->assertStatus(200)
        ->assertSee('Helpdesk PuTI')
        ->assertSee('Buat Tiket Baru')
        ->assertSee('Tiket Saya');
});

test('user can submit valid local ticket and ticket number is generated', function () {
    $user = User::factory()->create();

    $payload = [
        'category' => 'Jaringan & Internet',
        'service_item' => 'Wi-Fi Kampus (Eduroam / TelU-Guest)',
        'title' => 'Koneksi Wi-Fi Sering Terputus di Perpustakaan',
        'description' => 'Sinyal eduroam sering putus setiap 5 menit ketika menggunakan laptop di area sofa perpustakaan lantai 2.',
        'location' => 'Gedung Perpustakaan Lt. 2',
        'priority' => 'high',
    ];

    $response = $this->actingAs($user)->post('/ticket', $payload);

    $ticket = Ticket::where('title', $payload['title'])->first();
    expect($ticket)->not->toBeNull();
    expect($ticket->ticket_number)->toStartWith('TIC-');
    expect($ticket->scope)->toBe('internal_surabaya');
    expect($ticket->status)->toBe('open');
    expect($ticket->authority)->toBe('local');

    $response->assertRedirect(route('ticket.show', $ticket));
});

test('submitting central authority ticket routes to waiting_central and pauses SLA', function () {
    $user = User::factory()->create();

    $payload = [
        'category' => 'Akun & Akses Terpadu',
        'service_item' => 'Akun iGracias & Single Sign-On (SSO)',
        'title' => 'Akun iGracias Terkunci Setelah Salah Password',
        'description' => 'Mohon bantuan untuk reset atau buka blokir akun iGracias saya karena salah memasukkan password lebih dari batas wajar.',
        'location' => null,
        'priority' => 'medium',
    ];

    $this->actingAs($user)->post('/ticket', $payload);

    $ticket = Ticket::where('title', $payload['title'])->first();
    expect($ticket)->not->toBeNull();
    expect($ticket->authority)->toBe('central');
    expect($ticket->scope)->toBe('escalated_central');
    expect($ticket->status)->toBe('waiting_central');
    expect($ticket->user_status_label)->toBe('Koordinasi Sistem Terpusat');
    expect($ticket->sla_paused_at)->not->toBeNull();

    // Ensure Luna posted an informative message
    $lunaMsg = $ticket->messages()->where('sender_type', 'ai_luna')->first();
    expect($lunaMsg)->not->toBeNull();
    expect($lunaMsg->message)->toContain('sistem terpusat');
});

test('submitting ambiguous local ticket routes to pending_user and requests clarification', function () {
    $user = User::factory()->create();

    $payload = [
        'category' => 'Jaringan & Internet',
        'service_item' => 'Wi-Fi Kampus (Eduroam / TelU-Guest)',
        'title' => 'Wi-Fi Rusak',
        'description' => 'wifi mati', // too short & missing location
        'location' => '',
        'priority' => 'low',
    ];

    $this->actingAs($user)->post('/ticket', $payload);

    $ticket = Ticket::where('title', $payload['title'])->first();
    expect($ticket)->not->toBeNull();
    expect($ticket->scope)->toBe('needs_clarification');
    expect($ticket->status)->toBe('pending_user');
    expect($ticket->user_status_label)->toBe('Menunggu Tanggapan Anda');
    expect($ticket->sla_paused_at)->not->toBeNull();

    // Ensure Luna posted a clarification request
    $lunaMsg = $ticket->messages()->where('sender_type', 'ai_luna')->first();
    expect($lunaMsg)->not->toBeNull();
    expect($lunaMsg->message)->toContain('Gedung');
});

test('user reply on pending_user ticket restores status to open and resumes sla', function () {
    $user = User::factory()->create();

    $ticket = Ticket::create([
        'ticket_number' => 'TIC-TEST-0001',
        'user_id' => $user->id,
        'category' => 'Jaringan & Internet',
        'service_item' => 'Wi-Fi Kampus (Eduroam / TelU-Guest)',
        'authority' => 'local',
        'scope' => 'needs_clarification',
        'status' => 'pending_user',
        'priority' => 'medium',
        'title' => 'Wi-Fi Problem',
        'description' => 'Internet mati',
        'sla_paused_at' => now()->subMinutes(30),
    ]);

    $this->actingAs($user)
        ->post("/ticket/{$ticket->id}/reply", [
            'message' => 'Lokasi saya di Gedung A Lantai 2 R.204, SSID TelU-Guest tidak muncul.',
        ])
        ->assertRedirect();

    $ticket->refresh();
    expect($ticket->status)->toBe('open');
    expect($ticket->scope)->toBe('internal_surabaya');
    expect($ticket->sla_paused_at)->toBeNull();
});

test('technician can manually override ticket scope between local and central', function () {
    $staff = User::factory()->create(['type' => 'Staf']);
    $user = User::factory()->create();

    $ticket = Ticket::create([
        'ticket_number' => 'TIC-TEST-0002',
        'user_id' => $user->id,
        'category' => 'Jaringan & Internet',
        'service_item' => 'Wi-Fi Kampus (Eduroam / TelU-Guest)',
        'authority' => 'local',
        'scope' => 'internal_surabaya',
        'status' => 'in_progress',
        'priority' => 'high',
        'title' => 'Access Point Rusak',
        'description' => 'Lampu AP mati total di Ruang B301.',
    ]);

    // 1. Override to central escalation
    $this->actingAs($staff)
        ->patch("/ticket/{$ticket->id}/override-scope", [
            'scope' => 'escalated_central',
            'central_ticket_ref' => 'PST-9988',
            'override_notes' => 'Ternyata controller AP di-manage langsung oleh tim Pusat Bandung.',
        ])
        ->assertRedirect();

    $ticket->refresh();
    expect($ticket->scope)->toBe('escalated_central');
    expect($ticket->status)->toBe('waiting_central');
    expect($ticket->central_ticket_ref)->toBe('PST-9988');

    // 2. Override back to local
    $this->actingAs($staff)
        ->patch("/ticket/{$ticket->id}/override-scope", [
            'scope' => 'internal_surabaya',
            'override_notes' => 'Ternyata PoE switch lokal Surabaya yang mati listrik, bukan controller pusat.',
        ])
        ->assertRedirect();

    $ticket->refresh();
    expect($ticket->scope)->toBe('internal_surabaya');
    expect($ticket->status)->toBe('in_progress');
});

test('luna live suggestion and chat endpoints return valid json responses', function () {
    $user = User::factory()->create();

    // Suggestion
    $this->actingAs($user)
        ->postJson('/ticket/luna/suggest', ['text' => 'Koneksi wifi lambat di lab'])
        ->assertStatus(200)
        ->assertJson([
            'success' => true,
            'suggestion' => [
                'category' => 'Jaringan & Internet',
                'service_item' => 'Wi-Fi Kampus (Eduroam / TelU-Guest)',
                'authority' => 'local',
            ],
        ]);

    // Interactive Chat
    $this->actingAs($user)
        ->postJson('/ticket/luna/chat', ['message' => 'Bagaimana cara connect ke eduroam?'])
        ->assertStatus(200)
        ->assertJsonStructure(['reply', 'timestamp']);
});

test('ticket assignment assigns present technician on duty today', function () {
    $technician = User::factory()->create(['type' => 'Staf', 'name' => 'Teknisi Siaga']);

    // Create attendance record for today using tanggal and jam_masuk
    Presence::create([
        'user_id' => $technician->id,
        'tanggal' => now()->format('Y-m-d'),
        'jam_masuk' => '08:00:00',
    ]);

    $assignmentService = app(TicketAssignmentService::class);
    $assigned = $assignmentService->assignTechnician();

    expect($assigned)->not->toBeNull();
    expect($assigned->id)->toBe($technician->id);
});
