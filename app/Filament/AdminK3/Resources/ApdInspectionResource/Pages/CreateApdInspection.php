<?php

namespace App\Filament\AdminK3\Resources\ApdInspectionResource\Pages;

use App\Filament\AdminK3\Resources\ApdInspectionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateApdInspection extends CreateRecord
{
    protected static string $resource = ApdInspectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Inspeksi berhasil dibuat';
    }
}
