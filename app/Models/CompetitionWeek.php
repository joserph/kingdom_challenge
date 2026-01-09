<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionWeek extends Model
{
    protected $fillable = [
        'name', 'date', 'week_number', 'status',
        'game_winner_team_id', 'game_points_winner',
        'observations'
    ];

    // RELATIONSHIP: A week has many scores
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    // RELATIONSHIP: Game winner (can be null)
    public function gameWinner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'game_winner_team_id');
    }

    // CALCULATED ATTRIBUTE: Blue team total points
    public function getBlueTeamPointsAttribute()
    {
        return $this->calculateTeamPoints('blue');
    }

    // CALCULATED ATTRIBUTE: Red team total points
    public function getRedTeamPointsAttribute()
    {
        return $this->calculateTeamPoints('red');
    }

    // PRIVATE METHOD: Calculate points by team color
    private function calculateTeamPoints($color)
    {
        $teamId = Team::where('color', 'like', "%$color%")->first()?->id;
        
        if (!$teamId) return 0;
        
        $individualPoints = $this->scores()
            ->whereHas('youth', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            })
            ->sum('total_points');
        
        // Add game points if this team won
        if ($this->game_winner_team_id == $teamId) {
            $individualPoints += $this->game_points_winner;
        }
        
        return $individualPoints;
    }
}
