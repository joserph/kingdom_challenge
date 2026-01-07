<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    protected $fillable = [
        'young_id', 'week_competiion_id',
        'assistance', 'punctuality', 'bible', 'guest',
        'total_points', 'observations'
    ];

    // RELACIÓN: Una puntuación pertenece a un joven
    public function young(): BelongsTo
    {
        return $this->belongsTo(Young::class);
    }

    // RELACIÓN: Una puntuación pertenece a una semana
    public function week(): BelongsTo
    {
        return $this->belongsTo(WeekCompetition::class, 'week_competiion_id');
    }

    // EVENTO: Calcular puntos antes de guardar
    protected static function booted()
    {
        static::saving(function ($score) {
            $points = 0;
            $points += $score->assistance ? 10 : 0;    // Asistencia: 10 pts
            $points += $score->punctuality ? 10 : 0;   // Puntualidad: 10 pts
            $points += $score->bible ? 5 : 0;         // Biblia: 5 pts
            $points += $score->guest ? 20 : 0;      // Invitado: 20 pts
            
            $score->total_points = $points;
        });
    }
}
