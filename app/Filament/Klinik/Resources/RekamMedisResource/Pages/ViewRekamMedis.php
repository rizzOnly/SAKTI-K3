<?php
namespace App\Filament\Klinik\Resources\RekamMedisResource\Pages;

use App\Filament\Klinik\Resources\RekamMedisResource;
use Filament\Resources\Pages\ViewRecord;

class ViewRekamMedis extends ViewRecord
{
    protected static string $resource = RekamMedisResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
