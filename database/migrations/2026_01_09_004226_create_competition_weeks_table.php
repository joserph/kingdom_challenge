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
        Schema::create('competition_weeks', function (Blueprint $table) {
            $table->id();
            // $table->json('games_data')->nullable()->after('game_points_winner');
            $table->string('name');
            $table->date('date');
            $table->integer('week_number');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->foreignId('game_winner_team_id')->nullable()->constrained('teams');
            $table->integer('game_points_winner')->default(0);
            $table->text('observations')->nullable();
            // $table->integer('total_games')->default(1)->after('game_points_winner');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_weeks');
    }
};
