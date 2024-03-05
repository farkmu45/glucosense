<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SymptomResource\Pages;
use App\Filament\Resources\SymptomResource\RelationManagers;
use App\Models\Symptom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SymptomResource extends Resource
{
    protected static ?string $model = Symptom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

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
                    ->searchable()
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
