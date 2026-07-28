<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSlot extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_active',
        'width',
        'height',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
    ];
}
