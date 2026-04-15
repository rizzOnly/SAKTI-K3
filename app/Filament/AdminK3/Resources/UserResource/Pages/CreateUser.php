<?php
namespace App\Filament\AdminK3\Resources\UserResource\Pages;

use App\Filament\AdminK3\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
