<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\AdmissionSetting;
use App\Models\DocumentRequirement;
use App\Models\Enums\AdmissionStatus;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['details'] = [];
        $admissionSetting = AdmissionSetting::find($data['admission_setting_id']);
        if ($admissionSetting) {
            foreach ($admissionSetting->requirements as $requirement) {
                if($requirement['for'] == 'student') {
                    $data['details'][$requirement['input']] = $data[$requirement['input']];
                    unset($data[$requirement['input']]);
                } elseif($requirement['for'] == 'parent') {
                    $data['parent']['details'][$requirement['input']] = $data[$requirement['input']];
                    unset($data[$requirement['input']]);
                }
            }
        }

        $documents = DocumentRequirement::where('admission_setting_id',$data['admission_setting_id'])->get();
        if($documents) {
            foreach ($documents as $document) {
                $data['documents'][$document->for][$document->id]['file_path'] = $data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id];
                $data['documents'][$document->for][$document->id]['document_requirement_id'] = $document->id;
                unset($data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id]);
            }
        }

        if (isset($data['parent'])) {
            session()->put('parent_data', $data['parent']);
            unset($data['parent']);
        }

        if(isset($data['documents'])) {
            session()->put('documents', $data['documents']);
            unset($data['documents']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $parentData = session()->pull('parent_data');
        if ($parentData) {
            $this->record->parent()->create([
                'father_name' => $parentData['father_name'],
                'father_occupation' => $parentData['father_occupation'],
                'mother_name' => $parentData['mother_name'],
                'mother_occupation' => $parentData['mother_occupation'],
                'details' => $parentData['details'] ?? null,
                'phone_number' => $parentData['phone_number']
            ]);
        }

        $documents = session()->pull('documents');
        if($documents) {
            if(isset($documents['student'])) {
                foreach ($documents['student'] as $document) {
                    $this->record->documents()->create([
                        'document_requirement_id' => $document['document_requirement_id'],
                        'file_path' =>  $document['file_path'],
                    ]);
                }
            }

            if(isset($documents['parent'])) {
                foreach ($documents['parent'] as $document) {
                    $this->record->parent->documents()->create([
                        'document_requirement_id' => $document['document_requirement_id'],
                        'file_path' =>  $document['file_path'],
                    ]);
                }
            }
        }

        $this->record->admission()->create([
            'admission_date' => Carbon::now(),
            'type' => AdmissionStatus::PENDING
        ]);
    }

}