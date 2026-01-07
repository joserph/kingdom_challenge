<?php

namespace App\Filament\Resources\Youngs\Pages;

use App\Filament\Resources\Youngs\YoungResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListYoungs extends ListRecords
{
    protected static string $resource = YoungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
