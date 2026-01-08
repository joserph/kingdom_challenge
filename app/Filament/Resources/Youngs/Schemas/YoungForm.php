<?php

namespace App\Filament\Resources\Youngs\Schemas;

use App\Models\Team;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class YoungForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Personal')
                    ->description('Datos básicos del joven')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nombre completo'),
                        
                        TextInput::make('age')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(30),
                        
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('+1234567890'),
                        
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('ejemplo@correo.com'),
                    ])->columns(2),
                
                Section::make('Participación en Competencia')
                    ->schema([
                        // Selector de equipo con creación rápida
                        Select::make('team_id')
                            ->label('Equipo')
                            ->relationship('team', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                ColorPicker::make('color')
                                    ->default('#3b82f6'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return Team::create($data)->id;
                            })
                            ->helperText('Selecciona un equipo o crea uno nuevo'),
                        
                        Toggle::make('active')
                            ->default(true)
                            ->label('Participa activamente')
                            ->helperText('Si está inactivo, no aparecerá en las semanas'),
                    ]),
            ]);
    }
}
