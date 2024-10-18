<?php

namespace App\Filament\Resources\AdmissionSettingResource\Pages;

use App\Filament\Resources\AdmissionSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Str;

class EditAdmissionSetting extends EditRecord
{
    protected static string $resource = AdmissionSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['title'], '-');
        return $data;
    }
}
