<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
