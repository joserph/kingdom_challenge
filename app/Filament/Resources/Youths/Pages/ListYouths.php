<?php

namespace App\Filament\Resources\Youths\Pages;

use App\Filament\Resources\Youths\YouthResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListYouths extends ListRecords
{
    protected static string $resource = YouthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
