<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    protected $fillable = [
        'youth_id', 'competition_week_id',
        'attendance', 'punctuality', 'bible', 'guest',
        'total_points', 'observations'
    ];

    // RELATIONSHIP: A score belongs to a youth
    public function youth(): BelongsTo
    {
        return $this->belongsTo(Youth::class);
    }

    // RELATIONSHIP: A score belongs to a week
    public function week(): BelongsTo
    {
        return $this->belongsTo(CompetitionWeek::class, 'competition_week_id');
    }

    // EVENT: Calculate points before saving
    protected static function booted()
    {
        static::saving(function ($score) {
            $points = 0;
            $points += $score->attendance ? 10 : 0;    // Attendance: 10 pts
            $points += $score->punctuality ? 10 : 0;   // Punctuality: 10 pts
            $points += $score->bible ? 5 : 0;          // Bible: 5 pts
            $points += $score->guest ? 20 : 0;         // Guest: 20 pts
            
            $score->total_points = $points;
        });
    }
}
