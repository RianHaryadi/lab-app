<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Nama Pengguna')
                    ->placeholder('Pilih pengguna utama')
                    ->relationship('user', 'name')
                    ->preload()
                    ->native(false)
                    ->required(),

                Select::make('backup_by')
                    ->label('Pengganti (Backup By)')
                    ->placeholder('Pilih pengguna pengganti (opsional)')
                    ->relationship('user', 'name')
                    ->preload()
                    ->native(false)
                    ->searchable(),

                DatePicker::make('date')
                    ->label('Tanggal')
                    ->native(false)
                    ->placeholder('Pilih tanggal absensi')
                    ->required(),

                TimePicker::make('check_in_time')
                    ->label('Jam Masuk')
                    ->seconds(false)
                    ->placeholder('Pilih jam masuk'),

                TimePicker::make('check_out_time')
                    ->label('Jam Keluar')
                    ->seconds(false)
                    ->placeholder('Pilih jam keluar'),

                Select::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'present' => 'Hadir',
                        'late'    => 'Terlambat',
                        'absent'  => 'Tidak Hadir',
                        'sick'    => 'Sakit',
                    ])
                    ->default('present')
                    ->required(),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->placeholder('Tambahkan catatan tambahan...')
                    ->columnSpanFull(),
            ]);
    }
}
