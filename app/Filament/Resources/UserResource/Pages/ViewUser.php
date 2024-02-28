<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('General Data')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('email'),
                    TextEntry::make('gender'),
                    TextEntry::make('age'),
                ])->columns(),
            Section::make('Diabetes Related Data')
                ->schema([
                    TextEntry::make('has_diabetes_history')
                        ->badge()
                        ->color(fn (int $state): string => match ($state) {
                            0 => 'success',
                            1 => 'danger',
                        })
                        ->formatStateUsing(function (int $state) {
                            switch ($state) {
                                case 0:
                                    return 'No';
                                    break;

                                case 1:
                                    return 'Yes';
                                    break;
                            }
                        })->columnSpanFull(),
                    TextEntry::make('last_glucose_check_date'),
                    TextEntry::make('last_glucose_check_value')
                        ->label('Last glucose check result'),
                ])->columns(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
