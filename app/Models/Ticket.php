<?php

namespace App\Models;

use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    /**
     * Customer-Centric Service Catalog:
     * Structured by user domain while mapping internal authority under the hood.
     */
    public const SERVICES = [
        'Jaringan & Internet' => [
            'Wi-Fi Kampus (Eduroam / TelU-Guest)' => ['authority' => 'local', 'icon' => 'wifi'],
            'Jaringan Kabel LAN Gedung / Ruangan' => ['authority' => 'local', 'icon' => 'cable'],
        ],
        'Akun & Akses Terpadu' => [
            'Akun iGracias & Single Sign-On (SSO)' => ['authority' => 'central', 'icon' => 'key-round'],
            'Email Kampus & Lisensi Microsoft 365' => ['authority' => 'central', 'icon' => 'mail'],
        ],
        'Aplikasi Pembelajaran' => [
            'LMS CeLOE & Perkuliahan Daring' => ['authority' => 'central', 'icon' => 'graduation-cap'],
            'Sistem Informasi Akademik Lokal' => ['authority' => 'local',   'icon' => 'layout-dashboard'],
        ],
        'Fasilitas & Lab IT' => [
            'Komputer Laboratorium / PC Kerja' => ['authority' => 'local', 'icon' => 'monitor'],
            'Proyektor / Perangkat Ruang Kelas' => ['authority' => 'local', 'icon' => 'projector'],
        ],
        'Lainnya' => [
            'Konsultasi / Kendala IT Lainnya' => ['authority' => 'local', 'icon' => 'help-circle'],
        ],
    ];

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'category',
        'service_item',
        'authority',
        'scope',
        'status',
        'priority',
        'location',
        'title',
        'description',
        'attachment',
        'central_ticket_ref',
        'override_notes',
        'sla_due_at',
        'sla_paused_at',
        'resolved_at',
    ];

    protected $casts = [
        'sla_due_at' => 'datetime',
        'sla_paused_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->orderBy('created_at', 'asc');
    }

    public function slaLogs(): HasMany
    {
        return $this->hasMany(TicketSlaLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * User-Friendly Status Label (Empathetic & Transparent)
     */
    public function getUserStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'Laporan Diterima',
            'pending_user' => 'Menunggu Tanggapan Anda',
            'in_progress' => 'Sedang Ditangani',
            'waiting_central' => 'Koordinasi Sistem Terpusat',
            'resolved' => 'Selesai Ditangani',
            'closed' => 'Tiket Ditutup',
            default => ucfirst($this->status),
        };
    }

    public function getUserStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => 'blue',
            'pending_user' => 'amber',
            'in_progress' => 'purple',
            'waiting_central' => 'orange',
            'resolved' => 'emerald',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'TIC-'.now()->format('Ym').'-';
        $latest = self::where('ticket_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;
        if ($latest && preg_match('/-(\d+)$/', $latest->ticket_number, $matches)) {
            $number = ((int) $matches[1]) + 1;
        }

        return $prefix.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }
}
