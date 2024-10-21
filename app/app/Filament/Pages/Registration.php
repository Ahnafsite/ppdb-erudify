<?php

namespace App\Filament\Pages;

use App\Models\AdmissionSetting;
use App\Models\DocumentRequirement;
use App\Models\Enums\AdmissionStatus;
use App\Models\ParentDocument;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\StudentParent;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Actions\Action as HeaderAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid as FormGrid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Auth\Access\AuthorizationException;

class Registration extends Page implements HasForms, HasInfolists, HasActions
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.registration';
    protected static ?string $modelLabel = 'Pendaftaran';
    protected static ?string $pluralModelLabel = 'Pendaftaran';

    protected static ?string $title = 'Pendaftaran Siswa Baru';

    public $student;
    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('student');
    }

    public function mount()
    {
        $user = Auth::user();
        $this->student = Student::where('user_id', $user->id)->first();
    }

    protected function getHeaderActions(): array
    {
        if($this->student->admission->type == AdmissionStatus::REVISION) {
            $this->student = $this->mutateformForFill($this->student);
            return [
                EditAction::make('edit')
                    ->record($this->student)
                    ->modalHeading('Edit Student Information')
                    ->form($this->studentForm())
                    ->action(function (array $data) {
                        $this->saveStudent($data);
                        try {
                            Notification::make('studentSaved')
                                ->title('Data berhasil disimpan')
                                ->success()
                                ->send();
                        } catch(\Exception $e) {
                            Notification::make('studentFailed')
                                ->title('Data gagal disimpan')
                                ->danger()
                                ->send();
                        }
                    })
            ];
        } else {
            return [];
        }
    }

    public function studentForm()
    {
        return [
            FormSection::make('Informasi Siswa')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->reactive(),
                    FileUpload::make('photo')
                        ->label('Foto Diri (3x4)')
                        ->imageCropAspectRatio('3:4')
                        ->image()
                        ->imageEditor()
                        ->acceptedFileTypes(['image/jpg', 'image/jpeg'])
                        ->directory('student-photo')
                        ->getUploadedFileNameForStorageUsing(
                            fn (TemporaryUploadedFile $file, Get $get): string => (string) str($file->getClientOriginalName())
                                ->prepend('student - ' . $get('name') . ' - ' . Carbon::now() . ' - '),
                        )
                        ->maxSize('1024')
                        ->imageEditorAspectRatios([
                            '3:4',
                        ])
                        ->downloadable()
                        ->required(),
                    FormGrid::make(2)
                    ->schema([
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir')
                            ->required(),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->required(),
                    ]),
                    Textarea::make('address')
                        ->label('Alamat Lengkap')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('phone_number')
                        ->label('Nomor Hp / Whatsapp')
                        ->prefix('+62')
                        ->placeholder('85123456789')
                        ->tel()
                        ->required(),
                    Radio::make('gender')
                        ->label('Jenis Kelamin')
                        ->options([
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                        ])
                        ->required()
                        ->columns(2),
                    Select::make('program_id')
                        ->label('Program Studi')
                        ->options(fn(Get $get) => Program::where('admission_setting_id', $get('admission_setting_id'))->get()->pluck('title', 'id'))
                        ->searchable()
                        ->required(),
                    FormGrid::make(1)
                        ->label('Informasi Tambahan')
                        ->schema(
                            function (Get $get) {
                                if ($get('admission_setting_id')) {
                                    $admissionSetting = AdmissionSetting::find($get('admission_setting_id'));
                                    if ($admissionSetting) {
                                        $schema = [];
                                        foreach ($admissionSetting->requirements as $requirement) {
                                            if($requirement['for'] == 'student') {
                                                $schema[] = TextInput::make($requirement['input'])
                                                    ->label($requirement['input'])
                                                    ->required($requirement['is_required']);
                                            }
                                        }
                                        return $schema;
                                    }
                                }
                                return [];
                            }
                        ),
                    ]),
            FormSection::make('Lampiran Siswa')
                ->schema(
                    function (Get $get) {
                        if ($get('admission_setting_id')) {
                            $documents = DocumentRequirement::where('admission_setting_id',$get('admission_setting_id'))->where('for', 'student')->get();
                            if ($documents) {
                                $schema = [];
                                foreach ($documents as $document) {
                                    $acceptedExtensions = collect($document->types)->map(function ($type) {
                                        switch ($type) {
                                            case 'application/pdf':
                                                return 'PDF';
                                            case 'image/jpeg':
                                                return 'JPEG';
                                            case 'image/jpg':
                                                return 'JPG';
                                            case 'image/png':
                                                return 'PNG';
                                            default:
                                                return strtoupper($type);
                                        }
                                    })->unique()->join(', ');
                                    $schema[] = FileUpload::make(strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id)
                                        ->label($document->name)
                                        ->acceptedFileTypes($document->types)
                                        ->maxSize(2048)
                                        ->directory('student-attachments')
                                        ->getUploadedFileNameForStorageUsing(
                                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                ->prepend('student - ' . $document->name . ' - ' . $get('name') . ' - ' . Carbon::now() . ' - '),
                                        )
                                        ->required($document->required)
                                        ->hint('File yang bisa diupload ' . $acceptedExtensions . ', Ukuran maksimal 2mb')
                                        ->previewable()
                                        ->downloadable();
                                }
                                return $schema;
                            }
                        }
                        return [];
                    }
                ),
            FormSection::make('Informasi Orang Tua')
                ->relationship('parent')
                ->schema([
                    FormGrid::make(2)
                        ->schema([
                            TextInput::make('father_name')
                                ->label('Nama Ayah')
                                ->required(),
                            TextInput::make('father_occupation')
                                ->label('Pekerjaan Ayah')
                                ->required(),
                        ]),
                    FormGrid::make(2)
                        ->schema([
                            TextInput::make('mother_name')
                                ->label('Nama Ibu')
                                ->required(),
                            TextInput::make('mother_occupation')
                                ->label('Pekerjaan Ibu')
                                ->required(),
                        ]),
                    TextInput::make('phone_number')
                        ->label('Nomer Hp / Whatsapp')
                        ->prefix('+62')
                        ->placeholder('85123456789')
                        ->tel()
                        ->required(),
                    ]),
                FormSection::make('Informasi Tambahan Orang Tua')
                    ->schema(
                        function (Get $get) {
                            if ($get('admission_setting_id')) {
                                $admissionSetting = AdmissionSetting::find($get('admission_setting_id'));
                                if ($admissionSetting) {
                                    $schema = [];
                                    foreach ($admissionSetting->requirements as $requirement) {
                                        if($requirement['for'] == 'parent') {
                                            $schema[] = TextInput::make($requirement['input'])
                                                ->label($requirement['input'])
                                                ->required($requirement['is_required']);
                                        }
                                    }
                                    return $schema;
                                }
                            }
                            return [];
                        }
                    ),
            FormSection::make('Lampiran Orang Tua')
                ->schema(
                    function (Get $get) {
                        if ($get('admission_setting_id')) {
                            $documents = DocumentRequirement::where('admission_setting_id',$get('admission_setting_id'))->where('for', 'parent')->get();
                            if ($documents) {
                                $schema = [];
                                foreach ($documents as $document) {
                                    $acceptedExtensions = collect($document->types)->map(function ($type) {
                                        switch ($type) {
                                            case 'application/pdf':
                                                return 'PDF';
                                            case 'image/jpeg':
                                                return 'JPEG';
                                            case 'image/jpg':
                                                return 'JPG';
                                            case 'image/png':
                                                return 'PNG';
                                            default:
                                                return strtoupper($type);
                                        }
                                    })->unique()->join(', ');
                                    $schema[] = FileUpload::make(strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id)
                                        ->label($document->name)
                                        ->acceptedFileTypes($document->types)
                                        ->maxSize(2048)
                                        ->directory('parent-attachments')
                                        ->getUploadedFileNameForStorageUsing(
                                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                ->prepend('parent-' . $document->name . '-' . $get('name') . ' - ' . Carbon::now() . ' - '),
                                        )
                                        ->required($document->required)
                                        ->hint('File yang bisa diupload ' . $acceptedExtensions . ', Ukuran maksimal 2mb')
                                        ->previewable()
                                        ->downloadable();
                                }
                                return $schema;
                            }
                        }
                        return [];
                    }
                )];
    }

    public function saveStudent($data)
    {
        $data['details'] = [];
        $admissionSetting = AdmissionSetting::find($this->student->admission_setting_id);
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

        $documents = DocumentRequirement::where('admission_setting_id',$this->student->admission_setting_id)->get();
        if($documents) {
            foreach ($documents as $document) {
                $data['documents'][$document->for][$document->id]['file_path'] = $data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id];
                $data['documents'][$document->for][$document->id]['document_requirement_id'] = $document->id;
                unset($data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id]);
            }
        }

        if($data['documents']) {
            if(isset($data['documents']['student'])) {
                foreach ($data['documents']['student'] as $document) {
                    $studentDocument = StudentDocument::where('document_requirement_id', $document['document_requirement_id'])->where('student_id', $this->student->id)->first();
                    if($studentDocument) {
                        $studentDocument->update([
                            'file_path' =>  $document['file_path'],
                        ]);
                    } else {
                        $this->student->documents()->create([
                            'document_requirement_id' => $document['document_requirement_id'],
                            'file_path' => $document['file_path'],
                        ]);
                    }
                }
            }

            if(isset($data['documents']['parent'])) {
                foreach ($data['documents']['parent'] as $document) {
                    $parentDocument = ParentDocument::where('document_requirement_id', $document['document_requirement_id'])->where('student_parent_id', $this->student->parent->id)->first();
                    if($parentDocument) {
                        $parentDocument->update([
                            'file_path' =>  $document['file_path'],
                        ]);
                    } else {
                        $this->student->parent->documents()->create([
                            'document_requirement_id' => $document['document_requirement_id'],
                            'file_path' => $document['file_path'],
                        ]);
                    }
                }
            }
        }

        $this->student->admission->update([
            'type' => AdmissionStatus::PENDING
        ]);

        unset($data['documents']);
        StudentParent::where('student_id', $this->student->id)->update($data['parent']);
        unset($data['parent']);
        Student::where('id', $this->student->id)->update($data);
        $this->student = Student::where('user_id', auth()->user()->id)->first();
    }

    protected function mutateformForFill(Student $student): Student
    {
        $admissionSetting = AdmissionSetting::find($student['admission_setting_id']);
        if ($admissionSetting) {
            foreach ($admissionSetting->requirements as $requirement) {
                if($requirement['for'] == 'student') {
                    if(array_key_exists($requirement['input'], $student['details'])) {
                        $student[$requirement['input']] = $student['details'][$requirement['input']];
                    }
                } elseif($requirement['for'] == 'parent') {
                    if(array_key_exists($requirement['input'], $this->student->parent['details'])) {
                        $student[$requirement['input']] = $this->student->parent['details'][$requirement['input']];
                    }
                }
            }
        }

        $documentRequirements = DocumentRequirement::where('admission_setting_id', $student['admission_setting_id'])->get();
        if($documentRequirements) {
            foreach ($documentRequirements as $document) {
                if($document->for == 'student') {
                    $studentDocument = StudentDocument::where('document_requirement_id', $document->id)->where('student_id', $student['id'])->first();
                    $student[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id] = $studentDocument ?  : null;
                } elseif($document->for == 'parent') {
                    $parentDocument = ParentDocument::where('document_requirement_id', $document->id)->where('student_parent_id', $this->student->parent['id'])->first();
                    $student[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id] = $parentDocument ?  : null;
                }
            }
        }
        return $student;
    }

    public function studentInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->student)
            ->schema([
                Section::make('Informasi Siswa')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                            Grid::make(3)
                                ->schema([
                                    ImageEntry::make('photo')
                                    ->label(''),
                                    TextEntry::make('admission.type')
                                        ->label('status')
                                        ->badge()
                                        ->color(function($record) {
                                            return match($record['admission']['type']) {
                                                AdmissionStatus::PENDING => 'warning',
                                                AdmissionStatus::REVISION => 'warning',
                                                AdmissionStatus::REJECTED => 'danger',
                                                AdmissionStatus::VERIFIED => 'success',
                                                AdmissionStatus::ACCEPTED => 'success',
                                                default => 'secondary'
                                            };
                                        }),
                                    TextEntry::make('admission.note')
                                        ->label('Catatan')
                                        ->getStateUsing(fn($record)=>$record->admission->note ? $record->admission->note : 'Tidak ada catatan'),
                                    ]),
                        ]),
                        TextEntry::make('name')
                            ->label('Nama'),
                        TextEntry::make('birth_day')
                            ->label('Tempat Tanggal Lahir')
                            ->getStateUsing(fn($record)=>$record->birth_place . ', ' . $record->birth_date->format('d F Y')),
                        TextEntry::make('address')
                            ->label('Alamat Lengkap'),
                        TextEntry::make('phone_number')
                            ->label('No Hp/Whatsapp'),
                        TextEntry::make('gender')
                            ->getStateUsing(function($record){
                                if($record->gender == 'L') {
                                    return 'Laki-laki';
                                } else {
                                    return 'Perempuan';
                                }
                            }),
                        ])->columns(2),
                    Section::make('Informasi Akademik')
                        ->schema(function($record) {
                            $details = [
                                TextEntry::make('program.title')
                                ->label('Program Studi'),
                            ];
                            if($record['details']) {
                                foreach($record['details'] as $key => $value) {
                                    $details[] = TextEntry::make('details.'.$key)
                                        ->label($key)
                                        ->default($value);
                                }
                            }
                            return $details;
                        }),
                    Section::make('Informasi Orang Tua')
                        ->schema(function($record) {
                                $details = [
                                    TextEntry::make('parent.father_name')
                                        ->label('Nama Ayah'),
                                    TextEntry::make('parent.father_occupation')
                                        ->label('Pekerjaan Ayah'),
                                    TextEntry::make('parent.mother_name')
                                        ->label('Nama Ibu'),
                                    TextEntry::make('parent.mother_occupation')
                                        ->label('Pekerjaan Ibu'),
                                ];
                                if($record['parent']['details']) {
                                    foreach($record['parent']['details'] as $key => $value) {
                                        $details[] = TextEntry::make('details.'.$key)
                                            ->label($key)
                                            ->default($value);
                                    }
                                }
                                return $details;
                            }
                        )->columns(2),
                    Split::make([
                        Section::make('Berkas Siswa')
                            ->schema([
                                RepeatableEntry::make('documents')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('documentRequirement.name')
                                            ->label('')
                                            ->suffixAction(
                                                Action::make('download')
                                                            ->icon('phosphor-download-simple-fill')
                                                            ->action(function($record){
                                                                $filePath = $record->file_path;
                                                                if (Storage::exists($filePath)) {
                                                                    return Storage::download($filePath);
                                                                } else {
                                                                    Notification::make('danger notification')
                                                                        ->title('File Tidak Tersedia');
                                                                }
                                                            })
                                            )

                                    ])
                        ]),
                        Section::make('Berkas Orang Tua')
                            ->schema([
                                RepeatableEntry::make('parent.documents')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('documentRequirement.name')
                                            ->label('')
                                            ->suffixAction(
                                                Action::make('download')
                                                            ->icon('phosphor-download-simple-fill')
                                                            ->action(function($record){
                                                                $filePath = $record->file_path;
                                                                if (Storage::exists($filePath)) {
                                                                    return Storage::download($filePath);
                                                                } else {
                                                                    Notification::make('danger notification')
                                                                        ->title('File Tidak Tersedia');
                                                                }
                                                            })
                                            )

                                    ])
                            ])
                    ])
            ]);
    }
}