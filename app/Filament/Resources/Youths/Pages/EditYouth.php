<?php

namespace App\Filament\Resources\Youths\Pages;

use App\Filament\Resources\Youths\YouthResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditYouth extends EditRecord
{
    protected static string $resource = YouthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
