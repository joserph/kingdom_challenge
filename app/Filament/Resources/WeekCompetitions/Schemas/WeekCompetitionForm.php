<?php

namespace App\Filament\Resources\WeekCompetitions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WeekCompetitionForm
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
                Select::make('state')
                    ->options(['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'])
                    ->default('pending')
                    ->required(),
                TextInput::make('winning_team_games')
                    ->numeric(),
                TextInput::make('points_games_winning_team')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('observations')
                    ->columnSpanFull(),
            ]);
    }
}
