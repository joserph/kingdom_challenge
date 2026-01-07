<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = [
        'name', 'icon', 'description', 'active'
    ];

    // RELACIÓN: Un equipo tiene muchos jóvenes
    public function youngs(): HasMany
    {
        return $this->hasMany(Young::class);
    }

    // RELACIÓN: Un equipo gana muchas semanas de juegos
    public function weeksWonGames()
    {
        return $this->hasMany(WeekCompetiion::class, 'winning_team_games');
    }

    // ATRIBUTO CALCULADO: Puntos totales del equipo
    public function getTotalPointsAttribute()
    {
        return $this->youngs()
            ->where('active', true)
            ->withSum('scores', 'total_points')
            ->get()
            ->sum('total_scores_points');
    }

    // ATRIBUTO CALCULADO: Jóvenes activos
    public function getYoungActiveAttribute()
    {
        return $this->youngs()->where('active', true)->count();
    }
}
