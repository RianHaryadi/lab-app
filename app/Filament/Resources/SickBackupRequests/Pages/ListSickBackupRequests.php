<?php

namespace App\Filament\Resources\SickBackupRequests\Pages;

use App\Filament\Resources\SickBackupRequests\SickBackupRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSickBackupRequests extends ListRecords
{
    protected static string $resource = SickBackupRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
