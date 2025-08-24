<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('user.name') // tampilkan nama user dari relasi
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // contoh: filter berdasarkan tanggal
                // Filter::make('today')->query(fn ($query) => $query->whereDate('date', today())),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(), // tombol tambah baru
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
