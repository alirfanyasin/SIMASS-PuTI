<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeTransfer extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }
}
