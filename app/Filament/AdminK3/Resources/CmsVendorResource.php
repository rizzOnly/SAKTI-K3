<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\CmsVendorResource\Pages;
use App\Models\CmsVendor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Toggle, DatePicker, Section, Grid, Repeater, Select};
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Tables\Actions\{EditAction, DeleteAction, DeleteBulkAction};

class CmsVendorResource extends Resource
{
    protected static ?string $model = CmsVendor::class;
    protected static ?string $navigationLabel = 'Vendor WPO PLUS';
    protected static ?string $navigationGroup = 'Vendor & Gate Access';
    protected static ?string $navigationIcon  = 'heroicon-o-building-office-2';
    protected static ?int    $navigationSort  = 12;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Data Vendor WPO PLUS')->schema([
                Grid::make(2)->schema([
                    TextInput::make('nama_vendor')
                        ->label('Nama Perusahaan / Vendor')
                        ->required(),

                    TextInput::make('nama_pekerjaan')
                        ->label('Nama Pekerjaan')
                        ->required()
                        ->placeholder('Contoh: Jasa Pemasangan Kanopi'),

                    TextInput::make('bidang_kerja')
                        ->label('Bidang Kerja')
                        ->required(),

                    TextInput::make('kontak')
                        ->label('No. Kontak / WA')
                        ->nullable(),

                    TextInput::make('email')
                        ->email()
                        ->nullable(),

                    Select::make('kategori')
                        ->options(['wpo_plus' => 'WPO PLUS', 'gate_access' => 'Gate Access'])
                        ->default('wpo_plus')
                        ->disabled()
                        ->dehydrated(),
                ]),
            ]),

            Section::make('Periode Pekerjaan')->schema([
                Grid::make(2)->schema([
                    DatePicker::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->nullable(),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        ->nullable()
                        ->afterOrEqual('tanggal_mulai'),
                ]),
            ]),

            Section::make('Daftar Pekerja WPO PLUS')
                ->description('Input nama pekerja vendor WPO PLUS beserta status asuransinya.')
                ->schema([
                    // Perhatikan: Namanya langsung menembak ke kolom database 'pekerja_json'
                    Repeater::make('pekerja_json')
                        ->label('')
                        ->schema([
                            Grid::make(3)->schema([
                                TextInput::make('nama')->label('Nama Pekerja')->required(),
                            ]),
                        ])
                        ->addActionLabel('+ Tambah Pekerja')
                        ->collapsible()
                        ->itemLabel(fn(array $state) => $state['nama'] ?? 'Pekerja baru')
                        ->default([]),
                ]),

            Section::make('Visibilitas')->schema([
                Toggle::make('is_active')
                    ->label('Tampil di Landing Page')
                    ->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_vendor')->label('Vendor')->searchable()->weight('semibold'),
                TextColumn::make('nama_pekerjaan')->label('Pekerjaan')->limit(40),
                TextColumn::make('tanggal_mulai')->label('Mulai')->date('d/m/Y'),
                TextColumn::make('tanggal_selesai')->label('Selesai')->date('d/m/Y')
                    ->color(fn($record) => $record->tanggal_selesai && $record->tanggal_selesai < today() ? 'danger' : null),
                TextColumn::make('kontak')->label('Kontak'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCmsVendors::route('/'),
            'create' => Pages\CreateCmsVendor::route('/create'),
            'edit'   => Pages\EditCmsVendor::route('/{record}/edit'),
        ];
    }
}
