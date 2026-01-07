<?php

namespace App\Filament\Resources\Youngs;

use App\Filament\Resources\Youngs\Pages\CreateYoung;
use App\Filament\Resources\Youngs\Pages\EditYoung;
use App\Filament\Resources\Youngs\Pages\ListYoungs;
use App\Filament\Resources\Youngs\Schemas\YoungForm;
use App\Filament\Resources\Youngs\Tables\YoungsTable;
use App\Models\Young;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class YoungResource extends Resource
{
    protected static ?string $model = Young::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return YoungForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return YoungsTable::configure($table);
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
            'index' => ListYoungs::route('/'),
            'create' => CreateYoung::route('/create'),
            'edit' => EditYoung::route('/{record}/edit'),
        ];
    }
}
