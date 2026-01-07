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
        Schema::create('week_competitions', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->date('date');
            $table->integer('week_number');
            $table->enum('state', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->foreignId('winning_team_games')->nullable()->constrained('teams');
            $table->integer('points_games_winning_team')->default(0);
            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('week_competitions');
    }
};
