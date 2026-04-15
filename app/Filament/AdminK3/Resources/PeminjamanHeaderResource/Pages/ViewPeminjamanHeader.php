<?php
namespace App\Filament\AdminK3\Resources\PeminjamanHeaderResource\Pages;

use App\Filament\AdminK3\Resources\PeminjamanHeaderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPeminjamanHeader extends ViewRecord
{
    protected static string $resource = PeminjamanHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
