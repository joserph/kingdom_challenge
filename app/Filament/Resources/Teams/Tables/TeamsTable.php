<?php

namespace App\Filament\Resources\Teams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TeamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    // Columna con color
                ColorColumn::make('color')
                    ->label('')
                    ->width(20),
                
                // Nombre con badge de color
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->descripcion)
                    ->weight('bold'),
                
                // Cantidad de jóvenes
                TextColumn::make('youngs_count')
                    ->counts('youngs')
                    ->label('Jóvenes')
                    ->sortable()
                    ->color('primary'),
                
                // Puntos totales
                TextColumn::make('total_points')
                    ->label('Puntos')
                    ->numeric()
                    ->sortable()
                    ->color('success'),
                
                // Estado activo
                IconColumn::make('active')
                    ->boolean()
                    ->label('Activo')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                TernaryFilter::make('active'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                ->before(function ($record) {
                    // Validar que no tenga jóvenes asignados
                    if ($record->youngs()->count() > 0) {
                        throw new \Exception('No se puede eliminar un equipo con jóvenes asignados.');
                    }
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
