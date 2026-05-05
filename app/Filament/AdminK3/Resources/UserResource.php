<?php

namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Select, Grid, Section};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\{EditAction, DeleteAction, DeleteBulkAction, RestoreAction, ForceDeleteAction};
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Manajemen Pegawai';
    protected static ?string $navigationGroup = 'Master Data K3';
    protected static ?string $navigationIcon   = 'heroicon-o-users';
    protected static ?int    $navigationSort   = 0;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identitas Pegawai')->schema([
                Grid::make(2)->schema([
                    TextInput::make('nid')
                        ->label('NID')
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
                        ->relationship('roles', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereIn('name', ['admin_k3', 'dokter', 'pegawai', 'perawat']))
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
                        ->helperText('Opsional'),

                    TextInput::make('no_hp')
                        ->label('Nomor WhatsApp')
                        ->tel()
                        ->nullable()
                        ->placeholder('08xxxxxxxxxx'),
                ]),
            ]),

            Section::make('Akses Sistem')->schema([
                Grid::make(2)->schema([
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $operation) => $operation === 'create'),

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
                TextColumn::make('nid')
                    ->label('NID')
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
                        'purple'  => 'perawat',
                    ]),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('no_hp')
                    ->label('No. WA')
                    ->icon('heroicon-m-phone'),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Filter Role'),
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (User $record) => $record->id === auth()->id())
                    ->action(function (User $record) {
                        try {
                            $record->delete();
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Pegawai dipindahkan ke sampah (Soft Delete).')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Terdapat kendala integritas database.')
                                ->danger()
                                ->send();
                        }
                    }),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $authId = auth()->id();
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->id !== $authId) {
                                    $record->delete();
                                    $count++;
                                }
                            }
                            Notification::make()
                                ->title("$count Pegawai berhasil dihapus.")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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
