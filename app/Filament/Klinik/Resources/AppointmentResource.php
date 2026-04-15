<?php
namespace App\Filament\Klinik\Resources;

use App\Filament\Klinik\Resources\AppointmentResource\Pages\CreateAppointment;
use App\Filament\Klinik\Resources\AppointmentResource\Pages\EditAppointment;
use App\Filament\Klinik\Resources\AppointmentResource\resourcePages\ListAppointments;
use App\Models\{KlinikAppointment, User};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{Select, DatePicker, Textarea};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Tables;

class AppointmentResource extends Resource
{
    protected static ?string $model = KlinikAppointment::class;
    protected static ?string $navigationLabel = 'Appointment';
    protected static ?string $navigationGroup = 'Pelayanan Klinik';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label('Pasien (NIP)')
                ->relationship('pasien', 'nip')
                ->searchable()
                ->required()
                ->getOptionLabelFromRecordUsing(fn($record) => "[{$record->nip}] {$record->name}"),

            Select::make('dokter_id')
                ->label('Dokter')
                ->options(User::role('dokter')->pluck('name', 'id'))
                ->required()
                ->reactive(),

            DatePicker::make('tanggal')
                ->minDate(now())
                ->required()
                ->reactive(),

            Select::make('jam_slot')
                ->label('Jam Slot')
                ->options(function (callable $get) {
                    $dokterId = $get('dokter_id');
                    $tanggal  = $get('tanggal');
                    if (!$dokterId || !$tanggal) return [];

                    $slots = KlinikAppointment::getSlotTersedia($dokterId, $tanggal);
                    return collect($slots)->mapWithKeys(fn($s) => [$s => $s])->toArray();
                })
                ->required()
                ->reactive()
                ->helperText('Hanya menampilkan slot yang belum terisi'),

            Textarea::make('keluhan')
                ->label('Keluhan Pasien')
                ->nullable(),

            Select::make('status')
                ->options([
                    'scheduled' => 'Terjadwal',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ])
                ->default('scheduled'),
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!KlinikAppointment::isSlotTersedia(
            $data['dokter_id'],
            $data['tanggal'],
            $data['jam_slot']
        )) {
            Notification::make()
                ->title('Slot sudah terisi!')
                ->body('Pilih jam atau tanggal lain.')
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pasien.name')
                    ->label('Pasien')
                    ->searchable(),

                TextColumn::make('pasien.nip')
                    ->label('NIP'),

                // TAMBAHAN: Kolom Bidang
                TextColumn::make('pasien.bidang')
                    ->label('Bidang')
                    ->default('-')
                    ->searchable(),

                TextColumn::make('dokter.name')
                    ->label('Dokter'),

                TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('jam_slot')
                    ->label('Jam'),

                TextColumn::make('keluhan')
                    ->label('Keluhan')
                    ->limit(40)
                    ->default('-'),

                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'scheduled',
                        'success' => 'completed',
                        'danger'  => 'cancelled',
                    ]),
            ])
            ->defaultSort('tanggal', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Terjadwal',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAppointments::route('/'),
            'create' => CreateAppointment::route('/create'),
            'edit'   => EditAppointment::route('/{record}/edit'),
        ];
    }
}
