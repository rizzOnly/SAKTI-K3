<?php

namespace App\Filament\Klinik\Resources\FitToWorkResource\Pages;

use App\Filament\Klinik\Resources\FitToWorkResource;
use Filament\Resources\Pages\ListRecords;

class ListFitToWorks extends ListRecords
{
    protected static string $resource = FitToWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [];
        // Create action di-disable karena pendaftaran dilakukan via form publik.
        // Jika diperlukan input manual via admin, bisa menambahkan CreateAction kembali.
    }
}
