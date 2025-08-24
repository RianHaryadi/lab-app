<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'user' => 'User'])
                    ->default('user')
                    ->required(),
                Select::make('user_type')
                    ->options(['programmer' => 'Programmer', 'assistant_lab' => 'Assistant lab']),    
                TextInput::make('password')
                    ->password()       // akan tampil sebagai input password
                    ->required()       // wajib diisi
                    ->dehydrateStateUsing(fn($state) => bcrypt($state)) // hash sebelum disimpan
                    ->label('Password'),
            ]);
    }
}
