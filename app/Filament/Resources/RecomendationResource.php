<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecomendationResource\Pages;
use App\Filament\Resources\RecomendationResource\RelationManagers;
use App\Models\Recomendation;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecomendationResource extends Resource
{
    protected static ?string $model = Recomendation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('disease')
                        ->options([
                            'TYPE_1' => 'Type 1 diabetes',
                            'TYPE_2' => 'Type 2 diabetes',
                        ])
                        ->required(),
                    RichEditor::make('recomendation')
                        ->label('Recomendation'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('disease')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'TYPE_2':
                                return 'Type 2 diabetes';
                            case 'TYPE_1':
                                return 'Type 1 diabetes';
                        }
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recomendation')
                    ->limit(40)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRecomendations::route('/'),
            'create' => Pages\CreateRecomendation::route('/create'),
            'edit' => Pages\EditRecomendation::route('/{record}/edit'),
        ];
    }
}
