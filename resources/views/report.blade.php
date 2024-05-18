<x-layouts.app>
  <div class="container mx-auto px-6 py-8">
    <div class="mb-8 rounded-lg border border-gray-100 bg-white px-5 py-6 shadow-md">
      <h2 class="mb-4 text-2xl font-bold">Glucosense</h2>
      <h2 class="mb-4 text-2xl font-bold">Diabetes Prediction Report</h2>
      <div class="mb-4 grid grid-cols-2">
        <p class="block"><span class="font-semibold">Name:</span> {{ $calculation->user->name }}</p>
        <p class="block"><span class="font-semibold">Date and time:</span>
          {{ $calculation->created_at->format('Y-m-d H:i') }}</p>
        <p class="block"><span class="font-semibold">Score:</span> {{ $calculation->value }}</p>
        <p class="block"><span class="font-semibold">Disease:</span>
          @switch($calculation->result)
            @case('TYPE_1')
              Type 1 diabetes
            @break

            @case('TYPE_2')
              Type 2 diabetes
            @break

            @case('TYPE_1_2')
              Either type 1 or type 2 diabetes
            @break

            @case('NONE')
              Healthy
            @break
          @endswitch
        </p>
      </div>
      <div class="mb-4">
        <h3 class="mb-2 text-lg font-semibold">Recommendations:</h3>
        <div class="list-disc pl-5">
          {!! \App\Models\Recomendation::find($calculation->result)->recomendation !!}
        </div>
      </div>
      <div>
        <h3 class="mb-2 text-lg font-semibold">Questionnaire:</h3>
        <ul class="list-disc pl-5">
          @foreach ($calculation->questionnaires as $questionnaire)
            <li>{{ $questionnaire->symptom->name }}: {{ $questionnaire->answer ? 'Yes' : 'No' }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</x-layouts.app>
