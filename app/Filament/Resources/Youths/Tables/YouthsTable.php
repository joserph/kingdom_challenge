<?php

namespace App\Filament\Resources\Youths\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class YouthsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                // REPLACE: TextColumn for age with birthdate column
                TextColumn::make('birthdate')
                    ->label('Birthdate')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                
                // ADD: Age column (calculated)
                TextColumn::make('age')
                    ->label('Age')
                    ->sortable(['birthdate']) // Sort by birthdate
                    ->getStateUsing(fn ($record): int => $record->age)
                    ->alignCenter(),
                
                TextColumn::make('team.name')
                    ->label('Team')
                    ->badge()
                    ->color(fn ($record) => $record->team->color ?? 'gray'),
                
                IconColumn::make('active')
                    ->boolean()
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                
                TextColumn::make('total_points')
                    ->label('Points')
                    ->numeric()
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
            ])
            ->filters([
                SelectFilter::make('team')
                    ->relationship('team', 'name'),
                
                TernaryFilter::make('active'),
                
                // ADD: Filter by age range
                Filter::make('age_range')
                    ->form([
                        TextInput::make('min_age')
                            ->numeric()
                            ->label('Minimum Age')
                            ->placeholder('13'),
                        TextInput::make('max_age')
                            ->numeric()
                            ->label('Maximum Age')
                            ->placeholder('25'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_age'],
                                fn (Builder $query, $minAge): Builder => 
                                    $query->whereDate('birthdate', '<=', now()->subYears($minAge))
                            )
                            ->when(
                                $data['max_age'],
                                fn (Builder $query, $maxAge): Builder => 
                                    $query->whereDate('birthdate', '>=', now()->subYears($maxAge))
                            );
                    }),
                
                // ADD: Filter by birthday month
                SelectFilter::make('birthday_month')
                    ->options([
                        '1' => 'January',
                        '2' => 'February',
                        '3' => 'March',
                        '4' => 'April',
                        '5' => 'May',
                        '6' => 'June',
                        '7' => 'July',
                        '8' => 'August',
                        '9' => 'September',
                        '10' => 'October',
                        '11' => 'November',
                        '12' => 'December',
                    ])
                    ->query(function (Builder $query, $data) {
                        if (!empty($data['value'])) {
                            $query->whereMonth('birthdate', $data['value']);
                        }
                    }),
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
