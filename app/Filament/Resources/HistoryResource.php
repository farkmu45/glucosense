<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\Pages;
use App\Models\Calculation;
use App\Models\Recomendation;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HistoryResource extends Resource
{
    protected static ?string $model = Calculation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'History';

    protected static ?string $pluralModelLabel = 'History';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->schema([
                        Tab::make('General')
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Name'),
                                TextEntry::make('created_at')
                                    ->label('Created at')
                                    ->date('Y-m-d'),
                                TextEntry::make('result')
                                    ->label('Diagnosis')
                                    ->formatStateUsing(function ($state) {
                                        switch ($state) {
                                            case 'TYPE_2':
                                                return 'Type 2 Diabetes';
                                            case 'TYPE_1':
                                                return 'Type 1 Diabetes';
                                            case 'TYPE_1_2':
                                                return 'Either Type 2 Diabetes Or Type 1 Diabetes';
                                            case 'NONE':
                                                return 'Normal';
                                        }
                                    }),
                                TextEntry::make('value'),
                            ])->columns(),
                        Tab::make('Recomendation')
                            ->schema([
                                TextEntry::make('result')
                                    ->hiddenLabel()
                                    ->formatStateUsing(function ($state) {
                                        switch ($state) {
                                            case 'TYPE_2':
                                                return Recomendation::find('TYPE_2')->recomendation;
                                            case 'TYPE_1':
                                                return Recomendation::find('TYPE_1')->recomendation;;
                                            default:
                                                return "None";
                                        }
                                    }),
                            ]),
                        Tab::make('Questionaire')
                            ->schema([
                                RepeatableEntry::make('questionnaires')
                                    ->label(null)
                                    ->schema([
                                        TextEntry::make('symptom.name'),
                                        IconEntry::make('answer')
                                            ->boolean(),
                                    ])
                                    ->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name'),
                TextColumn::make('created_at')
                    ->date('Y-m-d'),
                TextColumn::make('result')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'TYPE_2':
                                return 'Type 2 Diabetes';
                            case 'TYPE_1':
                                return 'Type 1 Diabetes';
                            case 'TYPE_1_2':
                                return 'Either Type 2 Diabetes Or Type 1 Diabetes';
                            case 'NONE':
                                return 'Normal';
                        }
                    }),
                TextColumn::make('value'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListHistories::route('/'),
            'view' => Pages\ViewHistory::route('/{record}'),
        ];
    }
}
