<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ModDependency extends Pivot
{
    protected $table = 'mod_dependencies';
    public $timestamps = true;

    protected $fillable = [
        'mod_id',
        'requires_mod_id',
    ];
}
