<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mod_pack_id',
        'is_upvote',
    ];

    protected $casts = [
        'is_upvote' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modPack(): BelongsTo
    {
        return $this->belongsTo(ModPack::class);
    }
}
