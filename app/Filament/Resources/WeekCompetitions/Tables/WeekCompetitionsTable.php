<?php

namespace App\Filament\Resources\WeekCompetitions\Tables;

use App\Filament\Resources\WeekCompetitions\WeekCompetitionResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WeekCompetitionsTable
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
                TextColumn::make('state')
                    ->badge(),
                TextColumn::make('winning_team_games')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('points_games_winning_team')
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
                    ->url(fn ($record) => WeekCompetitionResource::getUrl('points', ['record' => $record]))
                    ->visible(fn ($record) => $record->state != 'finalizada'),
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
