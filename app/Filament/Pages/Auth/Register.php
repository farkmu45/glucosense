<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                Radio::make('gender')
                    ->required()
                    ->options([
                        'MALE' => 'Male',
                        'FEMALE' => 'Female',
                    ])
                    ->inline(),
                TextInput::make('age')
                    ->maxValue(200)
                    ->numeric()
                    ->minValue(1)
                    ->required(),
                TextInput::make('weight')
                    ->suffix('kg')
                    ->numeric()
                    ->maxValue(1000)
                    ->minValue(1)
                    ->required(),
                Radio::make('has_diabetes_history')
                    ->label('Has diabetes history?')
                    ->required()
                    ->boolean()
                    ->inline(),
                TextInput::make('last_glucose_check_value')
                    ->label('Last glucose check result')
                    ->suffix('mg/dl')
                    ->maxValue(2000)
                    ->minValue(1)
                    ->numeric()
                    ->live(),
                DatePicker::make('last_glucose_check_date')
                    ->native(false)
                    ->maxDate(now())
                    ->disabled(fn (Get $get) => $get('last_glucose_check_value') == null)
                    ->requiredWith('last_glucose_check_value'),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
