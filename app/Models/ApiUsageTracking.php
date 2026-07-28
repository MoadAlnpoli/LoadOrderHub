<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUsageTracking extends Model
{
    protected $table = 'api_usage_tracking';

    protected $fillable = [
        'api_name',
        'date',
        'calls_count',
    ];

    protected $casts = [
        'date' => 'date',
        'calls_count' => 'integer',
    ];
}
