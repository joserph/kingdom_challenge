<?php

namespace App\Filament\Resources\Youths;

use App\Filament\Resources\Youths\Pages\CreateYouth;
use App\Filament\Resources\Youths\Pages\EditYouth;
use App\Filament\Resources\Youths\Pages\ListYouths;
use App\Filament\Resources\Youths\Schemas\YouthForm;
use App\Filament\Resources\Youths\Tables\YouthsTable;
use App\Models\Youth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class YouthResource extends Resource
{
    protected static ?string $model = Youth::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return YouthForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return YouthsTable::configure($table);
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
            'index' => ListYouths::route('/'),
            'create' => CreateYouth::route('/create'),
            'edit' => EditYouth::route('/{record}/edit'),
        ];
    }
}
