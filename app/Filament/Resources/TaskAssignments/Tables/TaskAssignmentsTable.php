<?php

namespace App\Filament\Resources\TaskAssignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class TaskAssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('deadline', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->label('Task Title')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('assignee.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->sortable(),

                TextColumn::make('deadline')
                    ->date('d M Y')
                    ->sortable(),

                IconColumn::make('deadline')
                    ->label('Overdue')
                    ->boolean()
                    ->sortable()
                    ->getStateUsing(fn ($record) => 
                        Carbon::parse($record->deadline)->isPast()
                    ),

                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'Pending',
                        'success' => 'Completed',
                        'warning' => 'In Progress',
                        'danger' => 'Overdue',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // contoh filter
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'Overdue' => 'Overdue',
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
