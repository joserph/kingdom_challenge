<?php

namespace App\Filament\Resources\Youngs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class YoungsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                TextColumn::make('age')
                    ->sortable()
                    ->alignCenter(),
                
                TextColumn::make('team.name')
                    ->label('Equipo')
                    ->badge()
                    ->color(fn ($record) => $record->equipo->color ?? 'gray'),
                
                IconColumn::make('active')
                    ->boolean()
                    ->label('Activo')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                
                TextColumn::make('total_points')
                    ->label('Puntos')
                    ->numeric()
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('team')
                    ->relationship('team', 'name'),
                
                TernaryFilter::make('active'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
