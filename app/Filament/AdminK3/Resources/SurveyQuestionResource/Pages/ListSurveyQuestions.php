<?php

namespace App\Filament\AdminK3\Resources\SurveyQuestionResource\Pages;

use App\Filament\AdminK3\Resources\SurveyQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyQuestions extends ListRecords
{
    protected static string $resource = SurveyQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
