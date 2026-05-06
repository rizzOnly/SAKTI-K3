<?php

namespace App\Filament\AdminK3\Resources\ApdInspectionResource\Pages;

use App\Filament\AdminK3\Resources\ApdInspectionResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Notifications\Notification;
use App\Models\ApdInspection;

class ListApdInspections extends ListRecords
{
    protected static string $resource = ApdInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_inspection')
                ->label('Buat Inspeksi Bulanan')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->action(function () {
                    $currentMonth = now()->format('Y-m');
                    $existing = ApdInspection::where('bulan', $currentMonth)->first();

                    if ($existing) {
                        Notification::make()
                            ->title('Laporan inspeksi bulan ini sudah ada')
                            ->body('Silakan edit inspeksi bulan ' . $currentMonth . ' yang sudah ada, atau pilih bulan lain.')
                            ->warning()
                            ->send();
                        return redirect()->route('filament.admin-k3.resources.apd-inspections.edit', $existing);
                    }

                    return redirect()->route('filament.admin-k3.resources.apd-inspections.create');
                }),
        ];
    }
}
