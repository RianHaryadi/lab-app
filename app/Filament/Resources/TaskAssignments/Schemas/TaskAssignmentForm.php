<?php

namespace App\Filament\Resources\TaskAssignments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TaskAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Tugas')
                    ->placeholder('Masukkan judul tugas')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Tambahkan deskripsi tugas...')
                    ->columnSpanFull(),

                Select::make('assigned_to')
                    ->label('Ditugaskan Kepada')
                    ->placeholder('Pilih pengguna')
                    ->relationship('assignee', 'name') // pastikan ada relasi assignedTo() di model
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('deadline')
                    ->label('Tenggat Waktu')
                    ->native(false)
                    ->required(),

                Select::make('status')
                    ->label('Status Tugas')
                    ->options([
                        'pending'     => 'Pending',
                        'in_progress' => 'Sedang Dikerjakan',
                        'done'        => 'Selesai',
                    ])
                    ->default('pending')
                    ->required(),

                Select::make('created_by')
                    ->label('Dibuat Oleh')
                    ->placeholder('Pilih pengguna pembuat')
                    ->relationship('creator', 'name') // pastikan ada relasi createdBy() di model
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
