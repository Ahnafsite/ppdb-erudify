<?php

namespace App\Filament\Resources\AdmissionSettingResource\Pages;

use App\Filament\Resources\AdmissionSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Str;

class CreateAdmissionSetting extends CreateRecord
{
    protected static string $resource = AdmissionSettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['title'], '-');
        return $data;
    }
}
