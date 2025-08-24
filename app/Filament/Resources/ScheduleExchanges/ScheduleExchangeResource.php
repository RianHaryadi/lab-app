<?php

namespace App\Filament\Resources\ScheduleExchanges;

use App\Filament\Resources\ScheduleExchanges\Pages\CreateScheduleExchange;
use App\Filament\Resources\ScheduleExchanges\Pages\EditScheduleExchange;
use App\Filament\Resources\ScheduleExchanges\Pages\ListScheduleExchanges;
use App\Filament\Resources\ScheduleExchanges\Pages\ViewScheduleExchange;
use App\Filament\Resources\ScheduleExchanges\Schemas\ScheduleExchangeForm;
use App\Filament\Resources\ScheduleExchanges\Schemas\ScheduleExchangeInfolist;
use App\Filament\Resources\ScheduleExchanges\Tables\ScheduleExchangesTable;
use App\Models\ScheduleExchange;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ScheduleExchangeResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Penjadwalan';

    protected static int|null $navigationSort = 2;

    protected static ?string $model = ScheduleExchange::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ubah jadwal';

    public static function form(Schema $schema): Schema
    {
        return ScheduleExchangeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ScheduleExchangeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScheduleExchangesTable::configure($table);
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
            'index' => ListScheduleExchanges::route('/'),
            'create' => CreateScheduleExchange::route('/create'),
            'view' => ViewScheduleExchange::route('/{record}'),
            'edit' => EditScheduleExchange::route('/{record}/edit'),
        ];
    }
}
