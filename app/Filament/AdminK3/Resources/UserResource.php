<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Select, Grid, Section};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions;
use Filament\Tables\Actions\{EditAction, DeleteAction, DeleteBulkAction};
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Manajemen Pegawai';
    protected static ?string $navigationGroup = 'Master Data K3';
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?int    $navigationSort  = 0;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identitas Pegawai')->schema([
                Grid::make(2)->schema([
                    TextInput::make('nip')
                        ->label('NIP')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(20),

                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required(),

                    TextInput::make('bidang')
                        ->label('Bidang / Bagian')
                        ->placeholder('Contoh: Produksi, Pemeliharaan, K3')
                        ->nullable(),

                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                        ->nullable(),

                    Select::make('roles')
                        ->label('Role (Akses Sistem)')
                        // Kita filter langsung dari database-nya!
                        ->relationship('roles', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereIn('name', ['admin_k3', 'dokter', 'pegawai']))
                        ->preload()
                        ->required()
                        ->native(false),
                    ]),
            ]),

            Section::make('Kontak')->schema([
                Grid::make(2)->schema([
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->nullable()
                        ->helperText('Opsional — untuk notifikasi email'),

                    TextInput::make('no_hp')
                        ->label('Nomor WhatsApp')
                        ->tel()
                        ->nullable()
                        ->placeholder('08xxxxxxxxxx')
                        ->helperText('Opsional — untuk notifikasi WhatsApp'),
                ]),
            ]),

            Section::make('Akses Sistem')->schema([
                Grid::make(2)->schema([
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $operation) => $operation === 'create')
                        ->helperText('Kosongkan jika tidak ingin mengubah password'),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password')
                        ->password()
                        ->same('password')
                        ->required(fn(string $operation) => $operation === 'create')
                        ->dehydrated(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('bidang')
                    ->label('Bidang')
                    ->searchable()
                    ->placeholder('-'),

                BadgeColumn::make('roles.name')
                    ->label('Role')
                    ->colors([
                        'primary' => 'admin_k3',
                        'success' => 'dokter',
                        'warning' => 'pegawai',
                    ]),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('—')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('gray'),

                TextColumn::make('no_hp')
                    ->label('No. WA')
                    ->placeholder('—')
                    ->icon('heroicon-m-phone')
                    ->iconColor('gray'),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Filter Role')
                    ->options([
                        'admin_k3' => 'Admin K3',
                        'dokter'   => 'Dokter',
                        'pegawai'  => 'Pegawai',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function ($record) {
                        if ($record->id === auth()->id()) {
                            Notification::make()
                                ->title('Tidak bisa menghapus akun sendiri!')
                                ->danger()
                                ->send();
                            $this->halt();
                        }
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
            'import' => Pages\ImportUsers::route('/import'),
        ];
    }
}
