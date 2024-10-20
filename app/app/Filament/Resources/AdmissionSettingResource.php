<?php

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\AdmissionSettingResource\Pages;
use App\Filament\Resources\AdmissionSettingResource\RelationManagers;
use App\Models\AdmissionSetting;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AdmissionSettingResource extends Resource
{
    protected static ?string $model = AdmissionSetting::class;

    protected static ?string $navigationIcon = 'phosphor-gear-light';
    protected static ?string $modelLabel = 'PPDB';
    protected static ?string $pluralModelLabel = 'PPDB';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view-any AdmissionSetting');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Umum')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun Ajaran')
                            ->placeholder('2024/2025')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Poster (4:3)')
                            ->imageCropAspectRatio('4:3')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpg', 'image/jpeg'])
                            ->directory('ppdb-image')
                            ->getUploadedFileNameForStorageUsing(
                                                fn (TemporaryUploadedFile $file, Get $get): string => (string) str($file->getClientOriginalName())
                                                    ->prepend('ppdb - ' . $get('name') . ' - ' . Carbon::now() . ' - '),
                                            )
                            ->maxSize('1024')
                            ->imageEditorAspectRatios([
                                '4:3',
                            ])
                            ->downloadable()
                            ->required(),
                        TinyEditor::make('desc')
                            ->label('Deskripsi')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('uploads')
                            ->profile('default|simple|full|minimal|none|custom')
                            ->columnSpan('full')
                            ->required(),
                    ]),
                Section::make('Tanggal Pelaksanaan')
                    ->schema([
                        Forms\Components\DateTimePicker::make('admission_period_start')
                            ->label('Tanggal Buka')
                            ->seconds(false)
                            ->required(),
                        Forms\Components\DateTimePicker::make('admission_period_end')
                            ->label('Tanggal Tutup')
                            ->seconds(false)
                            ->required(),
                        Forms\Components\TextInput::make('contact_person.name')
                            ->label('Narahubung')
                            ->required(),
                        Forms\Components\TextInput::make('contact_person.no_hp')
                            ->label('No Hp/Whatsapp')
                            ->prefix('+62')
                            ->required(),
                    ])->columns(2),
                Section::make('Program Studi')
                    ->schema([
                        Repeater::make('programs')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextInput::make('code')
                                    ->label('Kode')
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Nama')
                                    ->required(),
                            ])
                    ])->columns(1),
                Section::make('Form Tambahan')
                    ->schema([
                        Repeater::make('requirements')
                            ->label('')
                            ->schema([
                                TextInput::make('input')
                                    ->label('Nama')
                                    ->required(),
                                Radio::make('for')
                                    ->label('Untuk')
                                    ->options([
                                        'student' => 'Siswa',
                                        'parent' => 'Orang Tua',
                                    ])
                                    ->required()
                                    ->columns(2),
                                Checkbox::make('is_required')
                                    ->label('Wajib')
                            ])
                    ])->columns(1),
                Section::make('Lampiran')
                    ->label('Lampiran')
                    ->schema([
                        Repeater::make('attachments')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lampiran')
                                    ->required(),
                                Radio::make('for')
                                    ->label('Untuk')
                                    ->options([
                                        'student' => 'Siswa',
                                        'parent' => 'Orang Tua',
                                    ])
                                    ->required()
                                    ->columns(2),
                                Select::make('types')
                                    ->label('Tipe')
                                    ->options([
                                        'application/pdf' => 'PDF',
                                        'image/jpg' => 'JPG',
                                        'image/png' => 'PNG',
                                        'image/jpeg' => 'JPEG',
                                    ])
                                    ->required()
                                    ->multiple()
                                    ->searchable(),
                                Checkbox::make('required')
                                    ->label('Wajib')
                            ])->cloneable()
                    ])->columns(1),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Pendaftaran')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission_period_start')
                    ->label('Tgl Buka')
                    ->datetime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('admission_period_end')
                    ->label('Tgl Tutup')
                    ->datetime()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAdmissionSettings::route('/'),
            'create' => Pages\CreateAdmissionSetting::route('/create'),
            'edit' => Pages\EditAdmissionSetting::route('/{record}/edit'),
        ];
    }
}
