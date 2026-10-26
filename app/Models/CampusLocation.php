<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampusLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'type',
        'name',
        'code',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CampusLocation::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CampusLocation::class, 'parent_id');
    }
}
