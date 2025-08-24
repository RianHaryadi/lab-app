<?php

namespace App\Filament\Resources\Todos\Pages;

use App\Filament\Resources\Todos\TodoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTodo extends ViewRecord
{
    protected static string $resource = TodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
