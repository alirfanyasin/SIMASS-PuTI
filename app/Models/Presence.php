<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'pekerjaan',
        'hari',
        'total_jam',
        'foto',
        'menit_tambahan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
    }

    public function overtimeTransfers()
    {
        return $this->hasMany(OvertimeTransfer::class);
    }
}
