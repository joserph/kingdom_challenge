<?php

namespace App\Filament\Resources\WeekCompetitions;

use App\Filament\Resources\WeekCompetitions\Pages\CreateWeekCompetition;
use App\Filament\Resources\WeekCompetitions\Pages\EditWeekCompetition;
use App\Filament\Resources\WeekCompetitions\Pages\ListWeekCompetitions;
use App\Filament\Resources\WeekCompetitions\Pages\RegisterPoints;
use App\Filament\Resources\WeekCompetitions\Schemas\WeekCompetitionForm;
use App\Filament\Resources\WeekCompetitions\Tables\WeekCompetitionsTable;
use App\Models\WeekCompetition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WeekCompetitionResource extends Resource
{
    protected static ?string $model = WeekCompetition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return WeekCompetitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WeekCompetitionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWeekCompetitions::route('/'),
            'create' => CreateWeekCompetition::route('/create'),
            'edit' => EditWeekCompetition::route('/{record}/edit'),
            // 'points' => RegisterPoints::route('/{record}/points'),
        ];
    }
}
