<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeTransfer extends Model
{
    protected $fillable = [
        'user_id',
        'overtime_id',
        'presence_id',
        'tanggal_transfer',
        'durasi_menit',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function overtime()
    {
        return $this->belongsTo(Overtime::class);
    }

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }
}
