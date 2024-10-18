<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\AdmissionSetting;
use App\Models\DocumentRequirement;
use App\Models\Enums\AdmissionStatus;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentDocument;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Tables\Filters\SelectFilter;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'phosphor-student';
    protected static ?string $modelLabel = 'Verifikasi Pendaftar';
    protected static ?string $pluralModelLabel = 'Verifikasi Pendaftar';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view-any Student');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Akun Siswa')
                    ->relationship('user')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(
                                table: 'users',
                                column: 'email',
                                ignorable: fn ($record) => $record
                            )
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->default(fn(Get $get) => $get('name'))
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state):string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->maxLength(255),
                    ]),
                Section::make('Informasi Siswa')
                    ->schema([
                        Forms\Components\Select::make('admission_setting_id')
                            ->label('Program Penerimaan')
                            ->options(AdmissionSetting::all()->pluck('title', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->reactive()
                            ->required(),
                        Forms\Components\FileUpload::make('photo')
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
                            Forms\Components\TextInput::make('birth_place')
                                ->label('Tempat Lahir')
                                ->required(),
                            Forms\Components\DatePicker::make('birth_date')
                                ->label('Tanggal Lahir')
                                ->required(),
                        ]),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('phone_number')
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
                        Forms\Components\Select::make('program_id')
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
                                                    $schema[] = Forms\Components\TextInput::make($requirement['input'])
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
                                        $schema[] = Forms\Components\FileUpload::make(strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id)
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
                                Forms\Components\TextInput::make('parent.father_name')
                                    ->label('Nama Ayah')
                                    ->required(),
                                Forms\Components\TextInput::make('parent.father_occupation')
                                    ->label('Pekerjaan Ayah')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('parent.mother_name')
                                    ->label('Nama Ibu')
                                    ->required(),
                                Forms\Components\TextInput::make('parent.mother_occupation')
                                    ->label('Pekerjaan Ibu')
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('parent.phone_number')
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
                                                    $schema[] = Forms\Components\TextInput::make($requirement['input'])
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
                                        $schema[] = Forms\Components\FileUpload::make(strtolower(str_replace(' ', '_', $document->name)) . '_' . $document->id)
                                            ->label($document->name)
                                            ->acceptedFileTypes($document->types)
                                            ->maxSize(2048)
                                            ->directory('/parent-attachments')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program.title')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission.type')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->actions([
                Action::make('verify')
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
                ->mountUsing(function (Form $form, $record) {
                    if ($record->admission) {
                        // Mengisi form dengan data dari relasi admission
                        $form->fill([
                            'type' => $record->admission->type,
                            'note' => $record->admission->note,
                        ]);
                    }
                })
                ->action(function(Array $data, $record) {
                    $record->admission()->update([
                        'type' => $data['type'],
                        'note' => $data['note'],
                    ]);

                    Notification::make('successVerification')
                        ->title('Berhasil Mengubah Status Verifikasi')
                        ->success()
                        ->send();
                }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
