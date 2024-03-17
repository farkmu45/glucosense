<?php

namespace App\Filament\User\Pages;

use App\Models\Symptom;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Calculation extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.calculation';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }


    public function form(Form $form): Form
    {
        $symptomps = Symptom::all()->toArray();

        $steps = array_map(fn ($symptom, $key) => Wizard\Step::make($key)
            ->label(null)
            ->schema([
                TextInput::make('question_id')
                    ->default($symptom['id'])
                    ->hidden(),
                Radio::make('answer')
                    ->required()
                    ->label($symptom['question'])
                    ->boolean()
            ]), $symptomps, array_keys($symptomps));

        return $form
            ->schema([
                Wizard::make(
                    $steps
                )
                ->submitAction(null)
            ])
            ->statePath('data');
    }
}
