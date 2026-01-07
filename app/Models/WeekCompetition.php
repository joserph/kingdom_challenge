<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeekCompetition extends Model
{
    protected $fillable = [
        'name', 'date', 'week_number', 'state',
        'winning_team_games', 'points_games_winning_team',
        'observations'
    ];

    // RELACIÓN: Una semana tiene muchas puntuaciones
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    // RELACIÓN: Ganador de juegos (puede ser null)
    public function teamWinner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winning_team_games');
    }

    // ATRIBUTO CALCULADO: Puntos totales del equipo azul
    public function getPuntosTotalAzulAttribute()
    {
        return $this->calcularPuntosEquipo('azul');
    }
}
