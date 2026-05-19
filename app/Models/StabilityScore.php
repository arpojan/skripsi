<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StabilityScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'enclosure_id',
        'analyzed_date',
        'range_compliance_score',
        'variability_score',
        'stability_duration_ratio',
        'fluctuation_penalty',
        'final_stability_score',
        'status',
    ];

    protected $casts = [
        'analyzed_date' => 'date',
        'range_compliance_score' => 'decimal:2',
        'variability_score' => 'decimal:2',
        'stability_duration_ratio' => 'decimal:2',
        'fluctuation_penalty' => 'decimal:2',
        'final_stability_score' => 'decimal:2',
    ];

    public function enclosure(): BelongsTo
    {
        return $this->belongsTo(Enclosure::class);
    }
}
