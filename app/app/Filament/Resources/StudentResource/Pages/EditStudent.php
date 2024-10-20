<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\AdmissionSetting;
use App\Models\DocumentRequirement;
use App\Models\Enums\AdmissionStatus;
use App\Models\ParentDocument;
use App\Models\StudentDocument;
use App\Models\StudentParent;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verify')
                ->label('Verifikasi')
                ->color('success')
                ->icon('heroicon-o-shield-check')
                ->form([
                    Select::make('type')
                        ->label('Verifikasi')
                        ->options(AdmissionStatus::class)
                        ->searchable()
                        ->required(),
                    Textarea::make('note')
                        ->label('Catatan')
                ])
                ->mountUsing(function (Form $form) {
                    if ($this->record->admission) {
                        // Mengisi form dengan data dari relasi admission
                        $form->fill([
                            'type' => $this->record->admission->type,
                            'note' => $this->record->admission->note,
                        ]);
                    }
                })
                ->action(function(Array $data) {
                    $this->record->admission()->update([
                        'type' => $data['type'],
                        'note' => $data['note'],
                    ]);

                    Notification::make('successVerification')
                        ->title('Berhasil Mengubah Status Verifikasi')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->admission) {
            $data['type'] = $this->record->admission->type;
            $data['note'] = $this->record->admission->note;
        }

        $parentData = StudentParent::where('student_id', $data['id'])->first();
        if($parentData) {
            $data['parent']['father_name'] = $parentData['father_name'];
            $data['parent']['father_occupation'] = $parentData['father_occupation'];
            $data['parent']['mother_name'] = $parentData['mother_name'];
            $data['parent']['mother_occupation'] = $parentData['mother_occupation'];
            $data['parent']['details'] = $parentData['details'] ?? null;
            $data['parent']['phone_number'] = $parentData['phone_number'];
        }

        $admissionSetting = AdmissionSetting::find($data['admission_setting_id']);
        if ($admissionSetting) {
            foreach ($admissionSetting->requirements as $requirement) {
                if($requirement['for'] == 'student') {
                    if(array_key_exists($requirement['input'], $data['details'])) {
                        $data[$requirement['input']] = $data['details'][$requirement['input']];
                    }
                } elseif($requirement['for'] == 'parent') {
                    if(array_key_exists($requirement['input'], $parentData['details'])) {
                        $data[$requirement['input']] = $parentData['details'][$requirement['input']];
                    }
                }
            }
        }

        $documentRequirements = DocumentRequirement::where('admission_setting_id', $data['admission_setting_id'])->get();
        if($documentRequirements) {
            foreach ($documentRequirements as $document) {
                if($document->for == 'student') {
                    $studentDocument = StudentDocument::where('document_requirement_id', $document->id)->where('student_id', $data['id'])->first();
                    $data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id] = $studentDocument ?  : null;
                } elseif($document->for == 'parent') {
                    $parentDocument = ParentDocument::where('document_requirement_id', $document->id)->where('student_parent_id', $parentData['id'])->first();
                    $data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id] = $parentDocument ?  : null;
                }
            }
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        $parentData = session()->pull('parent_data');
        if ($parentData) {
            $this->record->parent()->update([
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
            if(isset($document['student'])) {
                foreach ($documents['student'] as $document) {
                    $studentDocument = StudentDocument::where('document_requirement_id', $document['document_requirement_id'])->where('student_id', $this->record->id)->first();
                    if($studentDocument) {
                        $studentDocument->update([
                            'file_path' =>  $document['file_path'],
                        ]);
                    } else {
                        $this->record->documents()->create([
                            'document_requirement_id' => $document['document_requirement_id'],
                            'file_path' => $document['file_path'],
                        ]);
                    }
                }
            }

            if(isset($document['parent'])) {
                foreach ($documents['parent'] as $document) {
                    $parentDocument = ParentDocument::where('document_requirement_id', $document['document_requirement_id'])->where('student_parent_id', $this->record->parent->id)->first();
                    if($parentDocument) {
                        $parentDocument->update([
                            'file_path' =>  $document['file_path'],
                        ]);
                    } else {
                        $this->record->parent->documents()->create([
                            'document_requirement_id' => $document['document_requirement_id'],
                            'file_path' => $document['file_path'],
                        ]);
                    }
                }
            }
        }

        $this->record->admission()->create([
            'admission_date' => Carbon::now(),
            'type' => AdmissionStatus::PENDING
        ]);
    }
}
