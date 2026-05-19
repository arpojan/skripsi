<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enclosure_id')->constrained()->onDelete('cascade');
            $table->string('event_type');       // 'misting_on', 'misting_off', 'threshold_change', 'recommendation_accepted', etc.
            $table->text('description');
            $table->string('triggered_by');     // 'system', 'user', 'ai'
            $table->json('metadata')->nullable(); // Contextual event details (e.g., old/new values)
            $table->timestamps();

            $table->index(['enclosure_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_timelines');
    }
};
