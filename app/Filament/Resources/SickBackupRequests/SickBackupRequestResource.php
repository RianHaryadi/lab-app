<?php

namespace App\Filament\Resources\SickBackupRequests;

use App\Filament\Resources\SickBackupRequests\Pages\CreateSickBackupRequest;
use App\Filament\Resources\SickBackupRequests\Pages\EditSickBackupRequest;
use App\Filament\Resources\SickBackupRequests\Pages\ListSickBackupRequests;
use App\Filament\Resources\SickBackupRequests\Pages\ViewSickBackupRequest;
use App\Filament\Resources\SickBackupRequests\Schemas\SickBackupRequestForm;
use App\Filament\Resources\SickBackupRequests\Schemas\SickBackupRequestInfolist;
use App\Filament\Resources\SickBackupRequests\Tables\SickBackupRequestsTable;
use App\Models\SickBackupRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SickBackupRequestResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Penjadwalan';

    protected static int|null $navigationSort = 3;

    protected static ?string $model = SickBackupRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'backup';

    public static function form(Schema $schema): Schema
    {
        return SickBackupRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SickBackupRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SickBackupRequestsTable::configure($table);
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
            'index' => ListSickBackupRequests::route('/'),
            'create' => CreateSickBackupRequest::route('/create'),
            'view' => ViewSickBackupRequest::route('/{record}'),
            'edit' => EditSickBackupRequest::route('/{record}/edit'),
        ];
    }
}
