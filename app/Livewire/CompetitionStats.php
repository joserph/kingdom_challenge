<?php

namespace App\Livewire;

use App\Models\CompetitionWeek;
use App\Models\Team;
use App\Models\Youth;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CompetitionStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalYouths = Youth::where('active', true)->count();
        $completedWeeks = CompetitionWeek::where('status', 'completed')->count();
        
        $teams = Team::where('active', true)->withCount(['youths' => function ($query) {
            $query->where('active', true);
        }])->get();
        
        $stats = [];
        
        foreach ($teams as $team) {
            $stats[] = Stat::make($team->name, $team->youths_count)
                ->description($team->total_points . ' points')
                ->color('primary')
                ->icon($team->icon ?? 'heroicon-o-user-group');
        }

        return array_merge([
            Stat::make('Total Youths', $totalYouths)
                ->description('Active in competition')
                ->icon('heroicon-o-user-group'),
            
            Stat::make('Completed Weeks', $completedWeeks)
                ->description('Weeks finalized')
                ->icon('heroicon-o-trophy'),
        ], $stats);
    }
}
