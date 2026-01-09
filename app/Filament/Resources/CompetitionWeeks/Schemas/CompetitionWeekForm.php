<?php

namespace App\Filament\Resources\CompetitionWeeks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompetitionWeekForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('week_number')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'])
                    ->default('pending')
                    ->required(),
                TextInput::make('game_winner_team_id')
                    ->numeric(),
                TextInput::make('game_points_winner')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('observations')
                    ->columnSpanFull(),
            ]);
    }
}
