<?php

namespace App\Filament\Resources\CompetitionWeeks\Pages;

use App\Filament\Resources\CompetitionWeeks\CompetitionWeekResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionWeeks extends ListRecords
{
    protected static string $resource = CompetitionWeekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
