<?php

namespace App\Livewire;

use App\Models\AdmissionSetting;
use App\Models\DocumentRequirement;
use App\Models\Enums\AdmissionStatus;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Daftar extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public $slug;
    public $admission;

    public function mount(): void
    {
        $this->admission = AdmissionSetting::where('slug', $this->slug)->first();
        if(!$this->admission) {
            abort(404);
        }
        $this->data['admission_setting_id'] = $this->admission->id;
        $this->form->fill();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Buat Akun Siswa')
                    ->schema([
                        TextInput::make('user.email')
                            ->email()
                            ->unique(
                                table: 'users',
                                column: 'email',
                                ignorable: fn ($record) => $record
                            )
                            ->required()
                            ->maxLength(255),
                        TextInput::make('user.password')
                            ->password()
                            ->maxLength(255),
                    ]),
                Section::make('Informasi Siswa')
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
                        Grid::make(2)
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
                        Grid::make(1)
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
                Section::make('Lampiran Siswa')
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
                Section::make('Informasi Orang Tua')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('parent.father_name')
                                    ->label('Nama Ayah')
                                    ->required(),
                                TextInput::make('parent.father_occupation')
                                    ->label('Pekerjaan Ayah')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('parent.mother_name')
                                    ->label('Nama Ibu')
                                    ->required(),
                                TextInput::make('parent.mother_occupation')
                                    ->label('Pekerjaan Ibu')
                                    ->required(),
                            ]),
                        TextInput::make('parent.phone_number')
                            ->label('Nomer Hp / Whatsapp')
                            ->prefix('+62')
                            ->placeholder('85123456789')
                            ->tel()
                            ->required(),
                        Grid::make(1)
                            ->label('Informasi Tambahan')
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
                        ]),
                Section::make('Lampiran Orang Tua')
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
                    ),
            ])
            ->statePath('data')
            ->model(Student::class);
    }

    public function create()
    {
        $this->data = $this->form->getState();
        $this->data['details'] = [];
        $this->data['admission_setting_id'] = $this->admission->id;
        $admissionSetting = $this->admission;
        if ($admissionSetting) {
            foreach ($admissionSetting->requirements as $requirement) {
                if($requirement['for'] == 'student') {
                    if(isset($this->data[$requirement['input']])) {
                        $this->data['details'][$requirement['input']] = $this->data[$requirement['input']];
                    }
                    unset($this->data[$requirement['input']]);
                } elseif($requirement['for'] == 'parent') {
                    if(isset($this->data[$requirement['input']])) {
                        $this->data['parent']['details'][$requirement['input']] = $this->data[$requirement['input']];
                    }
                    unset($this->data[$requirement['input']]);
                }
            }
        }

        $documents = DocumentRequirement::where('admission_setting_id',$this->data['admission_setting_id'])->get();
        if($documents) {
            foreach ($documents as $document) {
                if(isset($this->data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id])) {
                    $this->data['documents'][$document->for][$document->id]['file_path'] = $this->data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id];
                    $this->data['documents'][$document->for][$document->id]['document_requirement_id'] = $document->id;
                }
                unset($this->data[strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id]);
            }
        }
        $this->data['user']['name'] = $this->data['name'];
        $user = User::create($this->data['user']);
        $user->assignRole('student');
        $this->data['user_id'] = $user->id;
        unset($this->data['user']);

        if(isset($this->data['documents'])) {
            $documents = $this->data['documents'];
            unset($this->data['documents']);
        }

        $parentData = $this->data['parent'];
        unset($this->data['parent']);

        $student = Student::create($this->data);
        if ($parentData) {
            $student->parent()->create([
                'father_name' => $parentData['father_name'],
                'father_occupation' => $parentData['father_occupation'],
                'mother_name' => $parentData['mother_name'],
                'mother_occupation' => $parentData['mother_occupation'],
                'details' => $parentData['details'] ?? null,
                'phone_number' => $parentData['phone_number']
            ]);
        }

        if($documents) {
            if(isset($documents['student'])) {
                foreach ($documents['student'] as $document) {
                    $student->documents()->create([
                        'document_requirement_id' => $document['document_requirement_id'],
                        'file_path' =>  $document['file_path'],
                    ]);
                }
            }

            if(isset($documents['parent'])) {
                foreach ($documents['parent'] as $document) {
                    $student->parent->documents()->create([
                        'document_requirement_id' => $document['document_requirement_id'],
                        'file_path' =>  $document['file_path'],
                    ]);
                }
            }
        }

        $student->admission()->create([
            'admission_date' => Carbon::now(),
            'type' => AdmissionStatus::PENDING
        ]);

        $this->form->fill();
        if(Auth::user()) {
            Auth::logout();
        }
        Auth::login($user);
        Notification::make()
            ->title('Berhasil Mendaftar')
            ->body('Silahkan tunggu pengumuman selanjutnya')
            ->success() // Mark the notification as a success type
            ->send();
        return redirect('/member')->with('success', 'Your account has been created and you have been logged in.');

    }

    public function render()
    {
        return view('livewire.daftar');
    }
}