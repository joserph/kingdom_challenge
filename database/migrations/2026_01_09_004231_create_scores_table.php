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
            
            $table->foreignId('youth_id')->constrained()->onDelete('cascade');
            $table->foreignId('competition_week_id')->constrained()->onDelete('cascade');
            $table->boolean('attendance')->default(false);
            $table->boolean('punctuality')->default(false);
            $table->boolean('bible')->default(false);
            $table->boolean('guest')->default(false);
            $table->integer('total_points')->default(0);
            $table->text('observations')->nullable();
            $table->timestamps();

            // A youth can only have one score per week
            $table->unique(['youth_id', 'competition_week_id']);
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
