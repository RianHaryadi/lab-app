<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Nama Pengguna'),

                TextEntry::make('date')
                    ->label('Tanggal')
                    ->date(),

                TextEntry::make('check_in_time')
                    ->label('Jam Masuk')
                    ->time(),

                TextEntry::make('check_out_time')
                    ->label('Jam Keluar')
                    ->time(),

                TextEntry::make('status')
                    ->label('Status Kehadiran')
                    ->badge()
                    ->colors([
                        'success' => 'present',
                        'warning' => 'late',
                        'danger'  => 'absent',
                        'info'    => 'sick',
                    ]),

                TextEntry::make('backupUser.name')
                    ->label('Pengganti')
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime(),
            ]);
    }
}
