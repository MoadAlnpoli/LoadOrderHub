<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralTracking extends Model
{
    protected $table = 'referral_tracking';

    protected $fillable = [
        'source',
        'date',
        'visits',
    ];

    protected $casts = [
        'date' => 'date',
        'visits' => 'integer',
    ];
}
