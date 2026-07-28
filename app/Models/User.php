<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'points',
        'badge_title',
        'avatar_url',
        'is_verified_curator',
    ];

    /**
     * Add points to user and automatically recalculate badge.
     */
    public function addPoints(int $amount): void
    {
        $this->increment('points', $amount);
        $this->recalculateBadge();
    }

    /**
     * Calculate badge title based on points.
     */
    public function recalculateBadge(): void
    {
        $pts = $this->points;
        $title = 'Novice Modder';

        if ($pts >= 1000) {
            $title = 'Legendary Curator 👑';
        } elseif ($pts >= 500) {
            $title = 'Master Load Orderer ⚔️';
        } elseif ($pts >= 250) {
            $title = 'Pro Modder 🛡️';
        } elseif ($pts >= 100) {
            $title = 'Enthusiast 🌟';
        }

        $this->update(['badge_title' => $title]);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function modPacks()
    {
        return $this->hasMany(ModPack::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
