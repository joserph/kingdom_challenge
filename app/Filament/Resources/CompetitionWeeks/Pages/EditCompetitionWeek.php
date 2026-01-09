<?php

namespace App\Filament\Resources\CompetitionWeeks\Pages;

use App\Filament\Resources\CompetitionWeeks\CompetitionWeekResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetitionWeek extends EditRecord
{
    protected static string $resource = CompetitionWeekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
