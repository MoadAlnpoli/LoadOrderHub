<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModPack extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'youtube_video_id',
        'youtube_thumbnail_url',
        'local_thumbnail_path',
        'views_count',
        'upvotes',
        'downvotes',
        'is_published',
        'is_private',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_private' => 'boolean',
        'views_count' => 'integer',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
    ];

    public function gameVersions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(GameVersion::class, 'game_version_mod_pack');
    }

    /**
     * Backward compatibility accessor for single version references
     */
    public function getGameVersionAttribute()
    {
        return $this->gameVersions->first();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function mods(): HasMany
    {
        return $this->hasMany(Mod::class)->orderBy('load_order', 'asc');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(PackRating::class);
    }

    /**
     * Calculate total size of all mods in this pack.
     */
    public function getTotalSizeGbAttribute(): string
    {
        $kb = $this->mods()->sum('file_size_kb');
        if ($kb <= 0) return 'Unknown';
        
        if ($kb < 1024) {
            return $kb . ' KB';
        } elseif ($kb < 1024 * 1024) {
            return number_format($kb / 1024, 2) . ' MB';
        }
        return number_format($kb / 1024 / 1024, 2) . ' GB';
    }

    /**
     * Check if any mod in the pack requires dependencies that are NOT in the pack.
     */
    public function getMissingDependenciesAttribute(): array
    {
        // For performance, we can load all mods and their dependencies
        $packMods = $this->mods()->with('dependencies')->get();
        $packModIds = $packMods->pluck('id')->toArray();
        $missing = [];
        
        foreach ($packMods as $mod) {
            foreach ($mod->dependencies as $dep) {
                if (!in_array($dep->id, $packModIds)) {
                    $missing[] = [
                        'required_by' => $mod,
                        'missing_mod' => $dep
                    ];
                }
            }
        }
        return $missing;
    }
}
