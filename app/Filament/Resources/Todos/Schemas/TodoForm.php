<?php

namespace App\Filament\Resources\Todos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use function Laravel\Prompts\select;

class TodoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('task')
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'in_progress' => 'In progress', 'done' => 'Done'])
                    ->default('pending')
                    ->required(),
                DatePicker::make('due_date'),
                Select::make('assigned_to')
                    ->relationship('assignee', 'name') // ❌ bukan 'assigned_to'
                    ->label('Nama Pengguna')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('updated_by')
                    ->numeric(),
            ]);
    }
}
