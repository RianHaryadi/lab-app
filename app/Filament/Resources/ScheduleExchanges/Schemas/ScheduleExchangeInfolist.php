<?php

namespace App\Filament\Resources\ScheduleExchanges\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\Grid;
class ScheduleExchangeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    TextEntry::make('schedule.title')
                        ->label('Schedule')
                        ->icon('heroicon-o-calendar'),

                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending'  => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default    => 'gray',
                        }),

                    TextEntry::make('fromUser.name')
                        ->label('Requested By')
                        ->icon('heroicon-o-user'),

                    TextEntry::make('toUser.name')
                        ->label('Requested To')
                        ->icon('heroicon-o-user'),

                    TextEntry::make('requested_at')
                        ->label('Requested At')
                        ->dateTime('d M Y H:i'),

                    TextEntry::make('approved_at')
                        ->label('Approved At')
                        ->dateTime('d M Y H:i'),

                    TextEntry::make('created_at')
                        ->label('Created At')
                        ->dateTime('d M Y H:i'),

                    TextEntry::make('updated_at')
                        ->label('Updated At')
                        ->dateTime('d M Y H:i'),
                        
            ]);
    }
}
