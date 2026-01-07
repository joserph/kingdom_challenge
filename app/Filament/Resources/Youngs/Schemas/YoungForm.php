<?php

namespace App\Filament\Resources\Youngs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class YoungForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('age')
                    ->required()
                    ->numeric(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
