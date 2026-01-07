<?php

namespace App\Filament\Resources\WeekCompetitions\Pages;

use App\Filament\Resources\WeekCompetitions\WeekCompetitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeekCompetitions extends ListRecords
{
    protected static string $resource = WeekCompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
