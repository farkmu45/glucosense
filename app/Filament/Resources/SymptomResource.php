<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SymptomResource\Pages;
use App\Models\Symptom;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SymptomResource extends Resource
{
    protected static ?string $model = Symptom::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(200)
                            ->required(),
                        TextInput::make('question')
                            ->maxLength(200)
                            ->required(),
                        TextInput::make('probability')
                            ->numeric()
                            ->maxValue(1)
                            ->columnSpanFull()
                            ->minValue(0)
                            ->required(),
                        Checkbox::make('is_diabetes_type_1')
                            ->columnSpanFull()
                            ->label('Diabetes type 1?'),
                        Checkbox::make('is_diabetes_type_2')
                            ->columnSpanFull()
                            ->label('Diabetes type 2?'),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_diabetes_type_1')
                    ->label('Diabetes type 1')
                    ->boolean()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_diabetes_type_2')
                    ->label('Diabetes type 2')
                    ->boolean()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('probability')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plausability')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListSymptoms::route('/'),
            'create' => Pages\CreateSymptom::route('/create'),
            'edit' => Pages\EditSymptom::route('/{record}/edit'),
        ];
    }
}
