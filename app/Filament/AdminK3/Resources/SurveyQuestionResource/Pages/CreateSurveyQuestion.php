<?php
namespace App\Filament\AdminK3\Resources\SurveyQuestionResource\Pages;

use App\Filament\AdminK3\Resources\SurveyQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyQuestion extends CreateRecord
{
    protected static string $resource = SurveyQuestionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
