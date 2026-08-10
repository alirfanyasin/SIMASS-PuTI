<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function recalculateTotalJam(): void
    {
        if (! $this->jam_masuk || ! $this->jam_pulang) {
            $this->total_jam = null;
            $this->save();

            return;
        }

        $masuk = Carbon::parse($this->tanggal.' '.$this->jam_masuk);
        $pulang = Carbon::parse($this->tanggal.' '.$this->jam_pulang);
        $actual = $masuk->diffInMinutes($pulang);

        $transferred = $this->overtimeTransfers()->sum('durasi_menit');

        $total = $actual + $transferred;

        $jam = floor($total / 60);
        $menit = $total % 60;

        $this->total_jam = "{$jam} Jam {$menit} Menit";
        $this->save();
    }
}
