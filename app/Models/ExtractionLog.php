<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtractionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'title',
        'transcript_fetched',
        'failure_reason',
        'is_valid_json',
        'total_mods_extracted',
        'low_confidence_count',
    ];

    protected $casts = [
        'transcript_fetched' => 'boolean',
        'is_valid_json' => 'boolean',
        'total_mods_extracted' => 'integer',
        'low_confidence_count' => 'integer',
    ];
}
