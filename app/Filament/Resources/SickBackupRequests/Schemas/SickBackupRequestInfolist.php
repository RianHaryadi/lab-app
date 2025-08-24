<?php

namespace App\Filament\Resources\SickBackupRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SickBackupRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sickUser.name')
                    ->label('Sick User'),

                TextEntry::make('originalSchedule.title')
                    ->label('Original Schedule'),

                TextEntry::make('date')
                    ->label('Tanggal Backup')
                    ->date('d/m/Y'),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'assigned',
                        'success' => 'approved',
                    ]),

                TextEntry::make('backup_by')
                    ->label('Backup By (User ID)')
                    ->relationship('backupUser', 'name'),

                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i'),

                TextEntry::make('updated_at')
                    ->label('Diupdate Pada')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
