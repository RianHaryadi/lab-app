<?php

namespace App\Filament\Resources\SickBackupRequests\Pages;

use App\Filament\Resources\SickBackupRequests\SickBackupRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSickBackupRequest extends ViewRecord
{
    protected static string $resource = SickBackupRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
