<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2) // dua kolom layout
            ->components([
                TextInput::make('name')
                    ->label('Project Name')
                    ->required()
                    ->placeholder('Masukkan nama proyek'),
                
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull()
                    ->placeholder('Deskripsi proyek...'),

                Select::make('users')
                    ->label('Assigned Users')
                    ->multiple() // bisa pilih lebih dari 1 user
                    ->relationship('users', 'name') // relasi many-to-many
                    ->preload() // load semua users dulu
                    ->searchable() // bisa cari user
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'active',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ])
                    ->default('active')
                    ->required(),

                DateTimePicker::make('deadline')
                    ->label('Deadline')
                    ->placeholder('Pilih tanggal dan waktu selesai proyek')
                    ->columnSpanFull(),
            ]);
    }
}
