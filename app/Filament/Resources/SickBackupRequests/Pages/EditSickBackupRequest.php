<?php

namespace App\Filament\Resources\SickBackupRequests\Pages;

use App\Filament\Resources\SickBackupRequests\SickBackupRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSickBackupRequest extends EditRecord
{
    protected static string $resource = SickBackupRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
