<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                DatePicker::make('date')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Nama Pengguna') 
                    ->searchable() 
                    ->preload() 
                    ->required(),
            ]);
    }
}
