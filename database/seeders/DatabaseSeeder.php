<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Recomendation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'email' => 'test@mail.com',
            'password' => 'password'
        ]);

        \App\Models\Admin::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
        ]);

        $this->call(SymptomSeeder::class);

        Recomendation::insert([
            ['disease' => 'TYPE_1', 'recomendation'
            => "<ul><li>Control blood sugar levels: People with type 1 diabetes need to control their blood sugar levels by using insulin regularly.&nbsp; Insulin use must be in accordance with the doctor's instructions.</li><li>Manage your diet: It is important to follow a healthy diet by paying attention to carbohydrate, fat and protein intake.&nbsp; Consult a nutritionist or doctor to get appropriate food recommendations.</li><li>Exercise: Regular exercise can help control blood sugar levels.&nbsp; However, keep in mind that exercise can affect blood sugar levels, so it is necessary to monitor blood sugar before, during and after exercise.</li><li>Manage stress: Stress can affect blood sugar levels.&nbsp; Find ways to manage stress, such as by meditation, yoga, or engaging in enjoyable activities.</li><li>Following treatment: It is important to follow the treatment prescribed by the doctor and have regular check-ups.</li></ul>"],
            ['disease' => 'TYPE_2', 'recomendation' => "<ul><li>Manage your diet: Focus on healthy foods with the right portions.&nbsp; Reduce consumption of foods that are high in sugar, saturated fat and salt.&nbsp; Prioritize foods that contain high fiber, such as vegetables, fruit and whole grains.</li><li>Exercise regularly: Get at least 150 minutes of physical activity per week.&nbsp; Choose an activity you like, such as walking, cycling or swimming.</li><li>Lose weight: If you are overweight or obese, healthy weight loss can help improve insulin sensitivity and control blood sugar levels.</li><li>Following treatment: If you have been diagnosed with type 2 diabetes, your doctor may prescribe medication to help control blood sugar levels.&nbsp; Make sure to follow the treatment according to the doctor's instructions.</li><li>Manage stress: Stress can affect blood sugar levels.&nbsp; Find ways to manage stress, such as with meditation, yoga, or engaging in an enjoyable hobby.</li><li>Avoid smoking and excessive alcohol consumption: Smoking and excessive alcohol consumption can worsen diabetes and increase the risk of complications.</li></ul>"],
        ]);
    }
}
