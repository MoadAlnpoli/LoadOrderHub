<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModReport extends Model
{
    protected $fillable = [
        'mod_id',
        'user_id',
        'reason',
        'status', // active, resolved
    ];

    public function mod(): BelongsTo
    {
        return $this->belongsTo(Mod::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
