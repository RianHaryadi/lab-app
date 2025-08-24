<?php

namespace App\Filament\Resources\TaskAssignments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TaskAssignmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Judul Tugas'),

                TextEntry::make('assignedTo.name')
                    ->label('Ditugaskan Kepada')
                    ->placeholder('-'),

                TextEntry::make('deadline')
                    ->label('Tenggat Waktu')
                    ->date(),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'in_progress',
                        'success' => 'done',
                    ]),

                TextEntry::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime(),
            ]);
    }
}
