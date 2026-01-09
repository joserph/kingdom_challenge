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
                // Color column
                ColorColumn::make('color')
                    ->label('')
                    ->width(20),
                
                // Name with color badge
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->description)
                    ->weight('bold'),
                
                // Youth count
                TextColumn::make('youths_count')
                    ->counts('youths')
                    ->label('Youths')
                    ->sortable()
                    ->color('primary'),
                
                // Total points
                TextColumn::make('total_points')
                    ->label('Points')
                    ->numeric()
                    ->sortable()
                    ->color('success'),
                
                // Active status
                IconColumn::make('active')
                    ->boolean()
                    ->label('Active')
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
                        // Validate no youths assigned
                        if ($record->youths()->count() > 0) {
                            throw new \Exception('Cannot delete a team with assigned youths.');
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
