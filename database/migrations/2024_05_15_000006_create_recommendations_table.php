<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enclosure_id')->constrained()->onDelete('cascade');
            $table->foreignId('insight_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->string('action_type');                                  // 'adjust_threshold', 'toggle_misting', 'investigate'
            // Current parameter values (snapshot saat rekomendasi dibuat)
            $table->decimal('current_bottom_rh', 5, 2)->nullable();
            $table->decimal('current_top_rh', 5, 2)->nullable();
            $table->integer('current_duration')->nullable();

            // AI-recommended values (nilai yang disarankan)
            $table->decimal('recommended_bottom_rh', 5, 2)->nullable();
            $table->decimal('recommended_top_rh', 5, 2)->nullable();
            $table->integer('recommended_duration')->nullable();

            $table->string('decision_status')->default('pending');          // 'pending', 'accepted', 'rejected'
            $table->timestamp('implemented_at')->nullable();
            $table->timestamps();

            $table->index(['enclosure_id', 'decision_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
