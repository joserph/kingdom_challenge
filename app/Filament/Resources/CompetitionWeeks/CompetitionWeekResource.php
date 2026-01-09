<?php

namespace App\Filament\Resources\CompetitionWeeks;

use App\Filament\Resources\CompetitionWeeks\Pages\CreateCompetitionWeek;
use App\Filament\Resources\CompetitionWeeks\Pages\EditCompetitionWeek;
use App\Filament\Resources\CompetitionWeeks\Pages\ListCompetitionWeeks;
use App\Filament\Resources\CompetitionWeeks\Schemas\CompetitionWeekForm;
use App\Filament\Resources\CompetitionWeeks\Tables\CompetitionWeeksTable;
use App\Models\CompetitionWeek;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompetitionWeekResource extends Resource
{
    protected static ?string $model = CompetitionWeek::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CompetitionWeekForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetitionWeeksTable::configure($table);
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
            'index' => ListCompetitionWeeks::route('/'),
            'create' => CreateCompetitionWeek::route('/create'),
            'edit' => EditCompetitionWeek::route('/{record}/edit'),
            // 'points' => RegisterPoints::route('/{record}/points'),
        ];
    }
}