<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameVersionHistory extends Model
{
    protected $table = 'game_version_history';

    protected $fillable = [
        'game_id',
        'version',
        'detected_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
