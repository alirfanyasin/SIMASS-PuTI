<?php

namespace App\Models;

use Database\Factories\TicketSlaLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketSlaLog extends Model
{
    /** @use HasFactory<TicketSlaLogFactory> */
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'paused_at',
        'resumed_at',
        'duration_minutes',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'paused_at' => 'datetime',
        'resumed_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
