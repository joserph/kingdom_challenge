<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('young_id')->constrained()->onDelete('cascade');
            $table->foreignId('week_competition_id')->constrained()->onDelete('cascade');
            $table->boolean('assistance')->default(false);
            $table->boolean('punctuality')->default(false);
            $table->boolean('bible')->default(false);
            $table->boolean('guest')->default(false);
            $table->integer('total_points')->default(0);
            $table->text('observations')->nullable();
            $table->timestamps();

            // Un joven solo puede tener una puntuación por semana
            $table->unique(['young_id', 'week_competition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
