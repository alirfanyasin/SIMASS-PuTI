<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'sla_resolution_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sla_resolution_hours' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
