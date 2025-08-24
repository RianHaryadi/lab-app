<?php

namespace App\Filament\Resources\Todos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TodosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'warning' => 'in_progress',
                        'success' => 'done',
                    ])
                    ->sortable(),

                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('assignee.name') // menampilkan nama user yang ditugaskan
                    ->label('Assigned To')
                    ->sortable(),

                TextColumn::make('creator.name') // menampilkan nama creator
                    ->label('Created By')
                    ->sortable(),

                TextColumn::make('updater.name') // menampilkan nama updater
                    ->label('Updated By')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
