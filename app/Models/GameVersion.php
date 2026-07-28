<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'version',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function modPacks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ModPack::class, 'game_version_mod_pack');
    }
}
