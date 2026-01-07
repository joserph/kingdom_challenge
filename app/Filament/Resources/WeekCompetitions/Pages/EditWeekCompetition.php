<?php

namespace App\Filament\Resources\WeekCompetitions\Pages;

use App\Filament\Resources\WeekCompetitions\WeekCompetitionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeekCompetition extends EditRecord
{
    protected static string $resource = WeekCompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
