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
        function DempsterRule($m1, $m2)
        {
            $result = array_fill_keys(array_merge(array_keys($m1), array_keys($m2)), 0);

            foreach ($m1 as $i => $value1) {
                foreach ($m2 as $j => $value2) {
                    if ($i == 0) { // Handling null hypothesis (theta) from $m1

                        $result[$j] += $m1[$i] * $m2[$j];
                    } elseif ($j == 0) { // Handling null hypothesis (theta) from $m2

                        $result[$i] += $m1[$i] * $m2[$j];
                    } elseif (array_intersect(str_split($i), str_split($j)) === str_split($i)) {
                        $result[$i] += $m1[$i] * $m2[$j];
                    } elseif (array_intersect(str_split($i), str_split($j)) === str_split($j)) {
                        $result[$j] += $m1[$i] * $m2[$j];
                    } else {
                        $key = array_intersect(str_split($i), str_split($j));
                        $keyString = implode('_', $key);
                        if ($keyString == "") {
                            $arr = [$i, $j];
                            sort($arr); // Sort the array in place
                            $keyString = implode('_', $arr);
                        }

                        if (!isset($result[$keyString])) {
                            $result[$keyString] = 0;
                        }
                        $result[$keyString] += $m1[$i] * $m2[$j];
                    }
                }
            }

            $f = array_sum(array_values($result));

            foreach ($result as $i => $value) {
                $result[$i] /= $f;
            }

            return $result;
        }

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

            // Retrieve symptoms with probabilities
            $symptoms = Symptom::whereIn('id', $questionArr)->get()->toArray();

            // Calculate belief function for each symptom
            $beliefFunctions = [];
            foreach ($symptoms as $index => $symptom) {
                if ($index == 0) {
                    $key = [];
                    if ($symptom['is_diabetes_type_1']) {
                        $key[] = 1;
                    }
                    if ($symptom['is_diabetes_type_2']) {
                        $key[] = 2;
                    }

                    $beliefFunctions[] = [implode('_', $key) => $symptom['probability'], "0" => $symptom['plausability']];
                } else {
                    $key = [];

                    if ($symptom['is_diabetes_type_1'] == 1) {
                        $key[] = 1;
                    }
                    if ($symptom['is_diabetes_type_2'] == 1) {
                        $key[] = 2;
                    }

                    $mi = [implode('_', $key) => $symptom['probability'], "0" => $symptom['plausability']];

                    $res = DempsterRule(end($beliefFunctions), $mi);
                    $beliefFunctions[] = $mi;
                    $beliefFunctions[] = $res;
                }
            }
            // dd($beliefFunctions); //delete this comment to see the value

            $lastMi = end($beliefFunctions);
            $lastMi[2] = $lastMi[2] ?? 0;

            // Ensure $lastMi[1] exists, if not, set it to 0
            $lastMi[1] = $lastMi[1] ?? 0;

            $value = 0;

            if ($lastMi[2] > $lastMi[1]) {
                $diabetesType = 'TYPE_2';
                $value = $lastMi[2];
            } else if ($lastMi[1] > $lastMi[2]) {
                $diabetesType = 'TYPE_1';
                $value = $lastMi[1];
            } else if ($lastMi['1_2'] > $lastMi[1] && $lastMi['1_2'] > $lastMi[2]) {
                $diabetesType = 'TYPE_1_2';
                $value = $lastMi['1_2'];
            } else {
                $diabetesType = 'NONE';
                $value = $lastMi[0];
            }

            $calculationResult = ModelsCalculation::create([
                'user_id' => auth()->user()->id,
                'result' => $diabetesType,
                'value' => $value
            ]);

            // Save questionnaires
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
            dd($th);
            Notification::make()
                ->title('An error occurred while calculating the data')
                ->danger()
                ->send();
        }
    }
}
