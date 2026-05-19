<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'enclosure_id',
        'insight_id',
        'title',
        'description',
        'action_type',
        'current_bottom_rh',
        'current_top_rh',
        'current_duration',
        'recommended_bottom_rh',
        'recommended_top_rh',
        'recommended_duration',
        'decision_status',
        'implemented_at',
    ];

    protected $casts = [
        'current_bottom_rh' => 'decimal:2',
        'current_top_rh' => 'decimal:2',
        'current_duration' => 'integer',
        'recommended_bottom_rh' => 'decimal:2',
        'recommended_top_rh' => 'decimal:2',
        'recommended_duration' => 'integer',
        'implemented_at' => 'datetime',
    ];

    public function enclosure(): BelongsTo
    {
        return $this->belongsTo(Enclosure::class);
    }

    public function insight(): BelongsTo
    {
        return $this->belongsTo(Insight::class);
    }
}
