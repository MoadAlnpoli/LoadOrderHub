<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
    ];

    public function mods(): HasMany
    {
        return $this->hasMany(Mod::class);
    }

    public function modPacks(): HasMany
    {
        return $this->hasMany(ModPack::class);
    }
}
