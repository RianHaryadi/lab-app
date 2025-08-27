<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Project Name'),

                TextEntry::make('users')
                    ->label('Assigned Users')
                    ->getStateUsing(fn ($record) => $record->users->pluck('name')->join(', ')),

                TextEntry::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => ucfirst(str_replace('_', ' ', $record->status))),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),

                TextEntry::make('deadline')
                    ->label('Deadline')
                    ->dateTime(),
            ]);
    }
}
