<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Mod extends Model
{
    use HasFactory;

    protected $fillable = [
        'mod_pack_id',
        'game_id',
        'category_id',
        'name',
        'slug',
        'description',
        'load_order',
        'nexus_url',
        'download_url',
        'steam_url',
        'image_url',
        'before_image_url',
        'after_image_url',
        'has_issues',
        'issues_count',
        'issues_note',
        'status',
        'nexus_mod_id',
        'downloads_count',
        'version',
        'author',
        'tags',
        'fps_impact',
        'local_image_path',
        'is_verified',
        'file_size_kb',
    ];

    protected $casts = [
        'load_order'       => 'integer',
        'has_issues'       => 'boolean',
        'issues_count'     => 'integer',
        'downloads_count'  => 'integer',
        'views_count'      => 'integer',
        'tags'             => 'array',
        'is_verified'      => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Mod $mod) {
            if (empty($mod->slug)) {
                $mod->slug = Str::slug($mod->name) ?: ('mod-' . Str::random(6));
            }
        });
    }

    /**
     * Get slug with robust fallback.
     */
    public function getSlugAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }
        return Str::slug($this->name ?? '') ?: ('mod-' . ($this->id ?? rand(100,999)));
    }

    public function modPack(): BelongsTo
    {
        return $this->belongsTo(ModPack::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Game versions this mod supports (many-to-many via pivot).
     */
    public function gameVersions(): BelongsToMany
    {
        return $this->belongsToMany(GameVersion::class, 'game_version_mod');
    }

    /**
     * Mods that this mod depends on.
     */
    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Mod::class,
            'mod_dependencies',
            'mod_id',
            'requires_mod_id'
        )->withTimestamps();
    }

    /**
     * Mods that depend on this mod.
     */
    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            Mod::class,
            'mod_dependencies',
            'requires_mod_id',
            'mod_id'
        )->withTimestamps();
    }

    /**
     * Reports filed against this mod.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(ModReport::class);
    }

    /**
     * Mods that conflict with this mod (many-to-many self-referential).
     */
    public function conflicts(): BelongsToMany
    {
        return $this->belongsToMany(
            Mod::class,
            'mod_conflicts',
            'mod_id',
            'conflicts_with_mod_id'
        )->withPivot(['reason_en', 'reason_ar'])->withTimestamps();
    }

    /**
     * Inverse conflicts: mods that list this mod as conflicting.
     */
    public function conflictedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            Mod::class,
            'mod_conflicts',
            'conflicts_with_mod_id',
            'mod_id'
        )->withPivot(['reason_en', 'reason_ar'])->withTimestamps();
    }

    /**
     * Check if this mod conflicts with another mod bidirectionally.
     */
    public function isConflictingWith($otherModId): bool
    {
        return \DB::table('mod_conflicts')
            ->where(function ($query) use ($otherModId) {
                $query->where('mod_id', $this->id)
                      ->where('conflicts_with_mod_id', $otherModId);
            })
            ->orWhere(function ($query) use ($otherModId) {
                $query->where('mod_id', $otherModId)
                      ->where('conflicts_with_mod_id', $this->id);
            })
            ->exists();
    }

    /**
     * Comments on this mod.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the best download URL available.
     */
    public function getBestUrlAttribute(): ?string
    {
        return $this->steam_url ?: $this->nexus_url ?: $this->download_url;
    }

    /**
     * Check if mod is flagged with issues.
     */
    public function getIsFlaggedAttribute(): bool
    {
        return (bool) $this->has_issues;
    }

    /**
     * Get the download source label.
     */
    public function getSourceLabelAttribute(): string
    {
        if ($this->steam_url) return 'Steam Workshop';
        if ($this->nexus_url && str_contains($this->nexus_url, 'nexusmods.com')) return 'Nexus Mods';
        if ($this->download_url) return 'Download';
        return 'N/A';
    }

    /**
     * Get the download source icon class.
     */
    public function getSourceIconAttribute(): string
    {
        if ($this->steam_url) return 'fa-brands fa-steam';
        if ($this->nexus_url) return 'fa-solid fa-download';
        return 'fa-solid fa-link';
    }
}
