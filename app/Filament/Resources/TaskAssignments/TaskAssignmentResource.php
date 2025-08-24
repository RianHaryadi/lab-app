<?php

namespace App\Filament\Resources\TaskAssignments;

use App\Filament\Resources\TaskAssignments\Pages\CreateTaskAssignment;
use App\Filament\Resources\TaskAssignments\Pages\EditTaskAssignment;
use App\Filament\Resources\TaskAssignments\Pages\ListTaskAssignments;
use App\Filament\Resources\TaskAssignments\Pages\ViewTaskAssignment;
use App\Filament\Resources\TaskAssignments\Schemas\TaskAssignmentForm;
use App\Filament\Resources\TaskAssignments\Schemas\TaskAssignmentInfolist;
use App\Filament\Resources\TaskAssignments\Tables\TaskAssignmentsTable;
use App\Models\TaskAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaskAssignmentResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen';
    
    protected static int|null $navigationSort = 3;
    protected static ?string $model = TaskAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tugas';

    public static function form(Schema $schema): Schema
    {
        return TaskAssignmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TaskAssignmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaskAssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTaskAssignments::route('/'),
            'create' => CreateTaskAssignment::route('/create'),
            'view' => ViewTaskAssignment::route('/{record}'),
            'edit' => EditTaskAssignment::route('/{record}/edit'),
        ];
    }
}
