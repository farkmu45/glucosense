<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Symptom::insert([
            [
                'name' => 'Often feel thirsty',
                'question' => 'Are you feeling excessively thirsty?',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => true,
                'probability' => 0.9,
                'plausability' => 0.1,
            ],
            [
                'name' => 'Often feel hungry',
                'question' => 'Are you feeling excessively hungry?',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => true,
                'probability' => 0.9,
                'plausability' => 0.1,
            ],
            [
                'name' => 'Lose weight',
                'question' => 'Have you experienced drastic weight loss?',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => true,
                'probability' => 0.9,
                'plausability' => 0.1,
            ],
            [
                'question' => 'Do you urinate more than 10 times a day?',
                'name' => 'Frequent urination',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => true,
                'probability' => 0.9,
                'plausability' => 0.1,
            ],
            [
                'name' => 'Dry mouth',
                'question' => 'Do you experience dry mouth?',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => true,
                'probability' => 0.2,
                'plausability' => 0.8,
            ],
            [
                'name' => 'Pain in the legs',
                'question' => 'Do you experience pain in your legs?',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => true,
                'probability' => 0.5,
                'plausability' => 0.5,
            ],
            [
                'name' => 'Experiencing reactive hypoglycemia',
                'question' => 'Did you experience hypoglycemia (drop in blood sugar)?',
                'is_diabetes_type_1' => false,
                'is_diabetes_type_2' => true,
                'probability' => 0.1,
                'plausability' => 0.9,
            ],
            [
                'name' => 'Frequently recurring infection',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => true,
                'question' => 'Do you have recurrent infections?',
                'probability' => 0.9,
                'plausability' => 0.1,
            ],
            [
                'name' => 'Erectile dysfunction or impotence',
                'is_diabetes_type_1' => true,
                'question' => 'Do you have erectile dysfunction / impotence?',
                'is_diabetes_type_2' => false,
                'probability' => 0.5,
                'plausability' => 0.5,
            ],
            [
                'name' => 'Itchy rash',
                'is_diabetes_type_1' => true,
                'is_diabetes_type_2' => false,
                'question' => 'Do you often have an itchy rash?',
                'probability' => 0,
                'plausability' => 1,
            ],

        ]);
    }
}
