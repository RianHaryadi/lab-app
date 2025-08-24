<?php

namespace App\Filament\Resources\ScheduleExchanges\Pages;

use App\Filament\Resources\ScheduleExchanges\ScheduleExchangeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewScheduleExchange extends ViewRecord
{
    protected static string $resource = ScheduleExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
