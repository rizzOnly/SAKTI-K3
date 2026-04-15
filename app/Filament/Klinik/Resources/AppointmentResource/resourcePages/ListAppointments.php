<?php
namespace App\Filament\Klinik\Resources\AppointmentResource\resourcePages;

use App\Filament\Klinik\Resources\AppointmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Appointment')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
