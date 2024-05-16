<?php

namespace App\Filament\Resources\RecomendationResource\Pages;

use App\Filament\Resources\RecomendationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecomendation extends EditRecord
{
    protected static string $resource = RecomendationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
