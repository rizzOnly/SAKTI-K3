<?php
namespace App\Filament\AdminK3\Resources\PengambilanHeaderResource\Pages;

use App\Filament\AdminK3\Resources\PengambilanHeaderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPengambilanHeader extends ViewRecord
{
    protected static string $resource = PengambilanHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
