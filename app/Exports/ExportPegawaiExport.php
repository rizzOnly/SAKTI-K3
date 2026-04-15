<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle};

class ExportPegawaiExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    private static int $no = 0;

    public function title(): string { return 'Daftar Pegawai'; }

    public function query()
    {
        return User::with('roles')->orderBy('name');
    }

    public function headings(): array
    {
        return ['No', 'NIP', 'Nama', 'Bidang', 'Role', 'Email', 'No. WA', 'Terdaftar'];
    }

    public function map($user): array
    {
        self::$no++;
        return [
            self::$no,
            $user->nip ?? '-',
            $user->name,
            $user->bidang ?? '-',
            $user->roles->pluck('name')->implode(', '),
            $user->email ?? '-',
            $user->no_hp ?? '-',
            $user->created_at->format('d/m/Y'),
        ];
    }
}
