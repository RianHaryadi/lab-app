<?php

namespace App\Filament\Resources\ScheduleExchanges\Pages;

use App\Filament\Resources\ScheduleExchanges\ScheduleExchangeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditScheduleExchange extends EditRecord
{
    protected static string $resource = ScheduleExchangeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
