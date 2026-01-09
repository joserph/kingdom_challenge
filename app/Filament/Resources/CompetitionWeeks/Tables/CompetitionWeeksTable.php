<?php

namespace App\Filament\Resources\CompetitionWeeks\Tables;

use App\Filament\Resources\CompetitionWeeks\CompetitionWeekResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompetitionWeeksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('week_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('game_winner_team_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('game_points_winner')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('register_points')
                    ->label('Register Points')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->url(fn ($record) => CompetitionWeekResource::getUrl('points', ['record' => $record]))
                    ->visible(fn ($record) => $record->status != 'completed'),
                
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
