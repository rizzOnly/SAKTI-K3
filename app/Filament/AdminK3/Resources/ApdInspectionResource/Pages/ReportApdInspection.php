<?php

namespace App\Filament\AdminK3\Resources\ApdInspectionResource\Pages;

use App\Filament\AdminK3\Resources\ApdInspectionResource;
use Filament\Resources\Pages\ViewRecord;
use App\Models\ApdInspection;

class ReportApdInspection extends ViewRecord
{
    protected static string $resource = ApdInspectionResource::class;
    protected static string $view = 'filament.admin-k3.pages.report-apd-inspection';

    public function getTitle(): string
    {
        return 'Laporan Inspeksi Bulanan APD - ' . $this->record->bulan;
    }

    public function getViewData(): array
    {
        $inspection = $this->record->load(['details.apdItem', 'user']);
        $details = $inspection->details;

        $grouped = $details->groupBy(function ($detail) {
            $name = $detail->apdItem->nama_barang;
            $parts = explode(' ', $name);
            return $parts[0] ?? $name;
        });

        return [
            'inspection' => $inspection,
            'groupedDetails' => $grouped,
            'totalItems' => $details->count(),
            'fitItems' => $details->where('is_tidak_layak', false)->count(),
            'unfitItems' => $details->where('is_tidak_layak', true)->count(),
            'totalUnfitCriteria' => $details->sum('unfit_criteria_count'),
        ];
    }
}
