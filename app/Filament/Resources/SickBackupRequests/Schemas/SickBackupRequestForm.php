<?php

namespace App\Filament\Resources\SickBackupRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use function Laravel\Prompts\select;

class SickBackupRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sick_user_id')
                    ->label('Sick User')
                    ->relationship('sickUser', 'name')
                    ->searchable()
                    ->placeholder('Pilih user yang sakit')
                    ->required(),

                Select::make('original_schedule_id')
                    ->label('Original Schedule')
                    ->relationship('originalSchedule', 'title')
                    ->searchable()
                    ->placeholder('Pilih jadwal asli')
                    ->required(),

                DatePicker::make('date')
                    ->label('Tanggal Backup')
                    ->displayFormat('d/m/Y')
                    ->native(false) // pakai flatpickr biar lebih modern
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'approved' => 'Approved',
                    ])
                    ->default('pending')
                    ->required(),

                select::make('backup_by')
                    ->label('Backup By (User ID)')
                    ->relationship('backupUser', 'name')
                    ->placeholder('Masukkan ID user pengganti'),
            ]);
    }
}
