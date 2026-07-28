<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackRating extends Model
{
    protected $fillable = [
        'mod_pack_id',
        'user_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function modPack(): BelongsTo
    {
        return $this->belongsTo(ModPack::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
