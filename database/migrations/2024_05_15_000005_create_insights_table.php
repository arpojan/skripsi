<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enclosure_id')->constrained()->onDelete('cascade');
            $table->string('type');           // e.g., 'humidity_trend', 'stability_drop', 'misting_pattern'
            $table->text('description');
            $table->string('severity');       // 'info', 'warning', 'critical'
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->index(['enclosure_id', 'generated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insights');
    }
};
