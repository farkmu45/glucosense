<?php

namespace App\Filament\User\Pages;

use App\Models\Calculation as ModelsCalculation;
use App\Models\Questionnaire;
use App\Models\Symptom;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

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

        $chunks = array_chunk($symptomps, 4);

        $steps = [];
        $inputs = [];
        $counter = 0;

        // Associate each input section
        foreach ($chunks as $key => $chunk) {
            $tempInputs = [];
            foreach ($chunk as $symptom) {
                $input = [
                    TextInput::make('symptom' . $counter)
                        ->default($symptom['id'])
                        ->hidden(),
                    Radio::make('answer' . $counter)
                        ->required()
                        ->label($symptom['question'])
                        ->boolean(),
                ];

                $counter++;
                array_push($tempInputs, $input);
            }

            array_push($inputs, $tempInputs);
            $tempInputs = [];
        }

        // Associate the steps with the input
        foreach ($chunks as $key => $chunk) {
            array_push($steps, Step::make($key)
                ->label(null)
                ->schema(array_merge(...$inputs[$key])));
        }

        return $form
            ->schema([
                Wizard::make(
                    $steps
                )
                    ->submitAction(
                        Action::make('submit')
                            ->label('Submit')
                            ->action('submit')
                            ->button()
                    ),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        try {
            // Convert to associative array
            $result = [];
            $data = $this->data;

            for ($i = 0; $i < count($data) / 2; $i++) {
                $questionKey = 'symptom' . $i;
                $answerKey = 'answer' . $i;
                array_push($result, ['symptom_id' => $data[$questionKey], 'answer' => (bool) $data[$answerKey]]);
            }

            // Filter any question with 'no' answer
            $filteredResult = Arr::where($result, function ($value, $key) {
                return $value['answer'];
            });

            // Get question id
            $questionArr = [];
            foreach ($filteredResult as $questionaire) {
                array_push($questionArr, $questionaire['symptom_id']);
            }

            $symptoms = Symptom::whereIn('id', $questionArr)->get()->toArray();

            // Calculate diabetes type based on symptoms

            $calculationResult = ModelsCalculation::create(['user_id' => auth()->user()->id, 'result' => 'TYPE_1']);
            foreach ($result as $key => $item) {
                $item['calculation_id'] = $calculationResult->id;
                Questionnaire::create($item);
            }

            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->send();

            $this->redirect(route('filament.user.pages.calculation'));
        } catch (\Throwable $th) {
            Notification::make()
                ->title('An error occured while calculating the data')
                ->danger()
                ->send();
        }
    }
}
