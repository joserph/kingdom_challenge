<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = [
        'name', 'color', 'light_color', 'dark_color',
        'icon', 'description', 'active'
    ];

    // RELATIONSHIP: A team has many youths
    public function youths(): HasMany
    {
        return $this->hasMany(Youth::class);
    }

    // RELATIONSHIP: A team wins many game weeks
    public function gameWins()
    {
        return $this->hasMany(CompetitionWeek::class, 'game_winner_team_id');
    }

    // CALCULATED ATTRIBUTE: Team total points
    public function getTotalPointsAttribute()
    {
        return $this->youths()
            ->where('active', true)
            ->withSum('scores', 'total_points')
            ->get()
            ->sum('scores_sum_total_points');
    }

    // CALCULATED ATTRIBUTE: Active youths count
    public function getActiveYouthsAttribute()
    {
        return $this->youths()->where('active', true)->count();
    }
}
