<?php

namespace App\Filament\Resources\SymptomResource\Pages;

use App\Filament\Resources\SymptomResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSymptom extends CreateRecord
{
    protected static string $resource = SymptomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['plausability'] = 1 - $data['probability'];
        return $data;
    }
}
