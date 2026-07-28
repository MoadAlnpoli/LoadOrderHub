<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'nexus_domain',
        'auto_import_enabled',
        'auto_import_limit',
        'last_imported_at',
        'latest_version',
    ];

    protected $casts = [
        'auto_import_enabled' => 'boolean',
        'auto_import_limit'   => 'integer',
        'last_imported_at'    => 'datetime',
    ];

    /**
     * Get the properly resolved thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $thumb = $this->thumbnail;
        if (empty($thumb)) {
            return 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=800&auto=format&fit=crop&q=80';
        }
        if (str_starts_with($thumb, 'http') || str_starts_with($thumb, 'data:')) {
            return $thumb;
        }
        return asset($thumb);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(GameVersion::class);
    }

    /**
     * All unique mods that belong to this game.
     */
    public function mods(): HasMany
    {
        return $this->hasMany(Mod::class);
    }

    /**
     * History of versions detected for this game.
     */
    public function versionHistory(): HasMany
    {
        return $this->hasMany(GameVersionHistory::class);
    }
}
