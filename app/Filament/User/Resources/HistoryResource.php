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
                            ]),
                        Tab::make('Recomendation')
                            ->schema([
                                TextEntry::make('result')
                                    ->hiddenLabel()
                                    ->markdown()
                                    ->formatStateUsing(function ($state) {
                                        switch ($state) {
                                            case 'TYPE_2':
                                                return "
- Manage your diet: Focus on healthy foods with the right portions.  Reduce consumption of foods that are high in sugar, saturated fat and salt.  Prioritize foods that contain high fiber, such as vegetables, fruit and whole grains.
- Exercise regularly: Get at least 150 minutes of physical activity per week.  Choose an activity you like, such as walking, cycling or swimming.
- Lose weight: If you are overweight or obese, healthy weight loss can help improve insulin sensitivity and control blood sugar levels.
- Following treatment: If you have been diagnosed with type 2 diabetes, your doctor may prescribe medication to help control blood sugar levels.  Make sure to follow the treatment according to the doctor's instructions.
- Manage stress: Stress can affect blood sugar levels.  Find ways to manage stress, such as with meditation, yoga, or engaging in an enjoyable hobby.
- Avoid smoking and excessive alcohol consumption: Smoking and excessive alcohol consumption can worsen diabetes and increase the risk of complications.
                                                ";
                                            case 'TYPE_1':
                                                return "
- Control blood sugar levels: People with type 1 diabetes need to control their blood sugar levels by using insulin regularly.  Insulin use must be in accordance with the doctor's instructions.
- Manage your diet: It is important to follow a healthy diet by paying attention to carbohydrate, fat and protein intake.  Consult a nutritionist or doctor to get appropriate food recommendations.
- Exercise: Regular exercise can help control blood sugar levels.  However, keep in mind that exercise can affect blood sugar levels, so it is necessary to monitor blood sugar before, during and after exercise.
- Manage stress: Stress can affect blood sugar levels.  Find ways to manage stress, such as by meditation, yoga, or engaging in enjoyable activities.
- Following treatment: It is important to follow the treatment prescribed by the doctor and have regular check-ups.
                                               ";
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
