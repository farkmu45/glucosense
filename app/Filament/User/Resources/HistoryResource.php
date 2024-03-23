<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\HistoryResource\Pages;
use App\Models\Calculation;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->schema([
                        Tab::make('General')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created at')
                                    ->date('Y-m-d'),
                                TextEntry::make('result')
                                    ->label('Diagnosis')
                                    ->formatStateUsing(fn ($state) => $state == 'TYPE_2' ? 'Diabetest Type 2' : 'Diabetes Type 1'),
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
                TextColumn::make('created_at')
                    ->date('Y-m-d'),
                TextColumn::make('result')
                    ->formatStateUsing(fn ($state) => $state == 'TYPE_2' ? 'Diabetest Type 2' : 'Diabetes Type 1'),

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
