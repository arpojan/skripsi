<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'enclosure_id',
        'event_type',
        'description',
        'triggered_by',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function enclosure(): BelongsTo
    {
        return $this->belongsTo(Enclosure::class);
    }
}
