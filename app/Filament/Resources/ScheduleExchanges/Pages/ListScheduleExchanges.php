<?php

namespace App\Filament\Resources\ScheduleExchanges\Pages;

use App\Filament\Resources\ScheduleExchanges\ScheduleExchangeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScheduleExchanges extends ListRecords
{
    protected static string $resource = ScheduleExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
