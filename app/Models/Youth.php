<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Youth extends Model
{
    protected $fillable = [
        'name', 'birthdate', 'phone', 'email', // Changed from 'age' to 'birthdate'
        'team_id', 'active'
    ];

    protected $casts = [
        'birthdate' => 'date', // Add date casting
    ];

    // RELATIONSHIP: A youth belongs to a team
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // RELATIONSHIP: A youth has many scores
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    // CALCULATED ATTRIBUTE: Calculate age from birthdate
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birthdate)->age;
    }

    // ✅ Additional: Days until next birthday
    public function getDaysUntilBirthdayAttribute()
    {
        $birthday = Carbon::parse($this->birthdate);
        $today = Carbon::now();
        
        $nextBirthday = $birthday->copy()->year($today->year);
        if ($nextBirthday->lt($today)) {
            $nextBirthday->addYear();
        }
        
        return $today->diffInDays($nextBirthday);
    }

    // CALCULATED ATTRIBUTE: Youth total points
    public function getTotalPointsAttribute()
    {
        return $this->scores()->sum('total_points');
    }

    // CALCULATED ATTRIBUTE: Team name (easy access)
    public function getTeamNameAttribute()
    {
        return $this->team->name ?? 'No team';
    }

    // CALCULATED ATTRIBUTE: Birthday this month
    public function getHasBirthdayThisMonthAttribute()
    {
        return Carbon::parse($this->birthdate)->month == Carbon::now()->month;
    }
}
