<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $fillable = [
        'user_id',
        'presence_id',
        'tanggal',
        'durasi_menit',
        'sisa_menit',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }

    public function transfers()
    {
        return $this->hasMany(OvertimeTransfer::class);
    }
}
