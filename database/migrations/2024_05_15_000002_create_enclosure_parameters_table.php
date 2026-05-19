<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enclosure_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enclosure_id')->constrained()->onDelete('cascade');
            // Biological environmental range (untuk stability analysis & RC)
            $table->decimal('humidity_min', 5, 2);
            $table->decimal('humidity_max', 5, 2);

            // Rule-based misting control thresholds
            $table->decimal('misting_bottom_threshold', 5, 2);
            $table->decimal('misting_top_threshold', 5, 2);
            $table->boolean('is_misting_auto')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enclosure_parameters');
    }
};
