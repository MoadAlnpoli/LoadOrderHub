<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'token', 'is_active'];

    protected static function booted(): void
    {
        static::creating(function ($sub) {
            if (empty($sub->token)) {
                $sub->token = Str::random(64);
            }
        });
    }
}
