<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\VendorRegistrasiResource\Pages;
use App\Models\VendorRegistrasi;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    TextInput, DatePicker, Toggle, Section, Grid,
    Repeater, FileUpload, Select
};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\{EditAction, DeleteAction, DeleteBulkAction, Action};
use Filament\Notifications\Notification;

class VendorRegistrasiResource extends Resource
{
    protected static ?string $model = VendorRegistrasi::class;
    protected static ?string $navigationLabel  = 'Registrasi Gate Access';
    protected static ?string $navigationGroup  = 'Vendor & Gate Access';
    protected static ?string $navigationIcon   = 'heroicon-o-identification';
    protected static ?int    $navigationSort   = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Pekerjaan')->schema([
                Grid::make(2)->schema([
                    TextInput::make('nama_perusahaan')
                        ->label('Nama Perusahaan / Vendor')
                        ->required(),

                    TextInput::make('nama_pekerjaan')
                        ->label('Nama Pekerjaan / Proyek')
                        ->required()
                        ->placeholder('Contoh: Jasa Pemasangan Kanopi Gudang Utama'),

                    DatePicker::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->required(),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        ->required()
                        ->after('tanggal_mulai'),
                ]),
            ]),

            Section::make('Kontak PIC')->schema([
                Grid::make(2)->schema([
                    TextInput::make('no_wa_pic')
                        ->label('No. WhatsApp PIC')
                        ->tel()
                        ->nullable()
                        ->placeholder('08xxxxxxxxxx'),

                    TextInput::make('email_pic')
                        ->label('Email PIC')
                        ->email()
                        ->nullable(),
                ]),
            ]),

            Section::make('Status')->schema([
                Grid::make(2)->schema([
                    Select::make('status')
                        ->options([
                            'aktif'    => 'Aktif',
                            'nonaktif' => 'Nonaktif',
                            'expired'  => 'Expired',
                        ])
                        ->default('aktif')
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Tampil di Landing Page')
                        ->default(true)
                        ->helperText('Admin bisa sembunyikan dari halaman publik'),
                ]),
            ]),

            Section::make('Daftar Pekerja')
                ->description('Pekerja yang sudah lulus survey akan muncul otomatis. Di sini admin bisa kelola manual.')
                ->schema([
                    Repeater::make('pekerjas')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Grid::make(3)->schema([
                                TextInput::make('nama_pekerja')
                                    ->label('Nama Pekerja')
                                    ->required()
                                    ->columnSpan(1),
                            ]),

                            Grid::make(2)->schema([
                                FileUpload::make('foto_pekerja')
                                    ->label('Foto Pekerja')
                                    ->image()
                                    ->directory('vendor/pekerja')
                                    ->nullable(),

                                Grid::make(1)->schema([
                                    Toggle::make('survey_lulus')
                                        ->label('Survey Lulus')
                                        ->helperText('Otomatis tercentang setelah pekerja lulus'),

                                    TextInput::make('survey_skor')
                                        ->label('Skor (%)')
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated(false),
                                ]),
                            ]),
                        ])
                        ->addActionLabel('+ Tambah Pekerja Manual')
                        ->collapsible()
                        ->itemLabel(fn(array $state) => $state['nama_pekerja'] ?? 'Pekerja baru'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_perusahaan')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('nama_pekerjaan')
                    ->label('Pekerjaan')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d/m/Y'),

                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d/m/Y')
                    ->color(fn($record) =>
                        $record->tanggal_selesai < today() ? 'danger' :
                        ($record->tanggal_selesai <= today()->addDays(7) ? 'warning' : null)
                    ),

                TextColumn::make('pekerjas_count')
                    ->label('Pekerja')
                    ->counts('pekerjas')
                    ->badge()
                    ->color('info'),

                TextColumn::make('pekerjas_lulus_count')
                    ->label('Lulus Survey')
                    ->getStateUsing(fn($record) => $record->pekerjasLulus()->count() . '/' . $record->pekerjas()->count())
                    ->badge()
                    ->color('success'),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'aktif',
                        'danger'  => fn($state) => in_array($state, ['nonaktif','expired']),
                    ]),

                IconColumn::make('is_active')
                    ->label('Publik')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(['aktif'=>'Aktif','nonaktif'=>'Nonaktif','expired'=>'Expired']),
            ])
            ->actions([
                EditAction::make(),

                Action::make('copy_link_survey')
                    ->label('Link Survey')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->action(function ($record) {
                        Notification::make()
                            ->title('Silakan Copy Link Survey Ini:')
                            ->body($record->survey_url)
                            ->success()
                            ->duration(10000) // Tampil 10 detik agar sempat dicopy
                            ->send();
                    })
                    ->tooltip('Tampilkan link survey untuk dikirim ke PIC vendor'),

                Action::make('toggle_aktif')
                    ->label(fn($record) => $record->is_active ? 'Sembunyikan' : 'Tampilkan')
                    ->icon(fn($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn($record) => $record->is_active ? 'warning' : 'success')
                    ->action(function ($record) {
                        $record->update(['is_active' => !$record->is_active]);
                        Notification::make()
                            ->title($record->is_active ? 'Registrasi ditampilkan di publik' : 'Registrasi disembunyikan dari publik')
                            ->success()->send();
                    }),

                DeleteAction::make(),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVendorRegistrasis::route('/'),
            'create' => Pages\CreateVendorRegistrasi::route('/create'),
            'edit'   => Pages\EditVendorRegistrasi::route('/{record}/edit'),
        ];
    }
}
