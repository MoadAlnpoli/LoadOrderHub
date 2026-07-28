<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoStaging extends Model
{
    use HasFactory;

    protected $table = 'video_staging';

    protected $fillable = [
        'video_id',
        'title',
        'description',
        'published_at',
        'game_id',
        'processed',
    ];

    protected $casts = [
        'processed' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
