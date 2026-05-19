<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stability_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enclosure_id')->constrained()->onDelete('cascade');
            $table->date('analyzed_date');
            $table->decimal('range_compliance_score', 5, 2);
            $table->decimal('variability_score', 5, 2);
            $table->decimal('stability_duration_ratio', 5, 2);
            $table->decimal('fluctuation_penalty', 5, 2);
            $table->decimal('final_stability_score', 5, 2);
            $table->string('status');           // 'Optimal', 'Stabil', 'Perhatian', 'Kritis'
            $table->timestamps();

            // Unique constraint: one score per enclosure per day
            $table->unique(['enclosure_id', 'analyzed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stability_scores');
    }
};
