<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECCIÓN 1: Información básica
                Section::make('Información del Equipo')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('icon')
                            ->required()
                            ->default('heroicon-o-user-group'),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        Toggle::make('active')
                            ->required(),
                    ])->columns(1),
                Section::make('Personalización')
                    ->description('Colores e icono para identificar el equipo')
                    ->schema([
                        ColorPicker::make('color')
                        ->label('Color Principal')
                        ->default('#3b82f6')
                        ->helperText('Color para botones y elementos destacados'),
                    
                    ColorPicker::make('color_claro')
                        ->label('Color de Fondo')
                        ->default('#dbeafe')
                        ->helperText('Color para fondos de tarjetas'),
                    
                    ColorPicker::make('color_oscuro')
                        ->label('Color de Texto')
                        ->default('#1e40af')
                        ->helperText('Color para textos sobre fondo claro'),
                    
                    Select::make('icono')
                        ->options([
                            'heroicon-o-flag' => '🚩 Bandera',
                            'heroicon-o-fire' => '🔥 Fuego',
                            'heroicon-o-shield-check' => '🛡️ Escudo',
                            'heroicon-o-trophy' => '🏆 Trofeo',
                            'heroicon-o-bolt' => '⚡ Rayo',
                            'heroicon-o-star' => '⭐ Estrella',
                            'heroicon-o-heart' => '❤️ Corazón',
                            'heroicon-o-users' => '👥 Grupo',
                        ])
                        ->default('heroicon-o-flag')
                        ->helperText('Ícono que representa al equipo'),
                    ])->columns(2),
            ]);
    }
}
