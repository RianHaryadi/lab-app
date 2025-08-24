<?php

namespace App\Filament\Resources\ScheduleExchanges\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ScheduleExchangeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2) // biar lebih rapi, form dibagi 2 kolom
            ->components([
                Select::make('schedule_id')
                    ->label('Schedule')
                    ->relationship('schedule', 'title')
                    ->placeholder('Pilih Schedule')
                    ->required(),

                Select::make('from_user_id')
                    ->label('Dari User')
                    ->relationship('fromUser', 'name')
                    ->searchable()
                    ->placeholder('Pilih User Pengaju')
                    ->required(),

                Select::make('to_user_id')
                    ->label('Ke User')
                    ->relationship('toUser', 'name')
                    ->searchable()
                    ->placeholder('Pilih User Tujuan')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),

                DateTimePicker::make('requested_at')
                    ->label('Tanggal Permintaan')
                    ->required(),

                DateTimePicker::make('approved_at')
                    ->label('Tanggal Disetujui'),
            ]);
    }
}
