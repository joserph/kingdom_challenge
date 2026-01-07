<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Young extends Model
{
    protected $fillable = [
        'name', 'age', 'phone', 'email',
        'team_id', 'active'
    ];

    // RELACIÓN: Un joven pertenece a un equipo
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // RELACIÓN: Un joven tiene muchas puntuaciones
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    // ATRIBUTO CALCULADO: Puntos totales del joven
    public function getTotalPointsAttribute()
    {
        return $this->scores()->sum('total_points');
    }

    // ATRIBUTO CALCULADO: Nombre del equipo (acceso fácil)
    public function getTeamNameAttribute()
    {
        return $this->team->name ?? 'Sin equipo';
    }
}
