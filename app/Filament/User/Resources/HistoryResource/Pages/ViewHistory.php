<?php

namespace App\Filament\User\Resources\HistoryResource\Pages;

use App\Filament\User\Resources\HistoryResource;
use App\Models\Calculation;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewHistory extends ViewRecord
{
    protected static string $resource = HistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make('report')
                ->label('Generate Report')
                ->action(
                    function (Calculation $record) {
                        $pdf = Pdf::loadView('report', ['calculation' => $record]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                            }, 'report.pdf');
                    }
                )
        ];
    }
}
