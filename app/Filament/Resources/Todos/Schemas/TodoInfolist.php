<?php

namespace App\Filament\Resources\Todos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TodoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('task')
                    ->label('Task'),

                TextEntry::make('status')
                    ->label('Status'),

                TextEntry::make('due_date')
                    ->label('Due Date')
                    ->date(),

                TextEntry::make('assignee.name')
                    ->label('Assigned To'),

                TextEntry::make('creator.name')
                    ->label('Created By'),

                TextEntry::make('updater.name')
                    ->label('Updated By'),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }
}
