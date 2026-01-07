<?php

namespace App\Filament\Resources\Youngs\Pages;

use App\Filament\Resources\Youngs\YoungResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditYoung extends EditRecord
{
    protected static string $resource = YoungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
