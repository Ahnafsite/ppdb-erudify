<?php

namespace App\Filament\Resources\AdmissionSettingResource\Pages;

use App\Filament\Resources\AdmissionSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdmissionSettings extends ListRecords
{
    protected static string $resource = AdmissionSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
