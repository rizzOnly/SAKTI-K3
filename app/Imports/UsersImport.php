<?php
namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{
    ToModel, WithHeadingRow, WithValidation,
    SkipsOnError, SkipsErrors, SkipsEmptyRows // <--- TAMBAHAN BARU
};
use Maatwebsite\Excel\Concerns\Importable;
use Throwable;

class UsersImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsEmptyRows // <--- TAMBAHAN BARU
{
    use Importable, SkipsErrors;

    private int $rowCount = 0;

    /**
     * Mengubah paksa angka menjadi teks (string) agar lolos validasi
     */
    public function prepareForValidation($data, $index)
    {
        if (isset($data['nip'])) {
            $data['nip'] = (string) trim(preg_replace('/\.0$/', '', $data['nip']));
        }
        if (isset($data['no_wa'])) {
            $data['no_wa'] = (string) trim(preg_replace('/\.0$/', '', $data['no_wa']));
        }
        return $data;
    }

    public function model(array $row): ?User
    {
        // Skip ekstra aman jika ada baris yang lolos namun kosong
        if (empty($row['nip']) || empty($row['nama'])) return null;

        $nipAsli = trim($row['nip']);

        // Cek apakah NIP sudah ada → update, belum ada → create
        $user = User::firstOrNew(['nip' => $nipAsli]);

        $user->name   = trim($row['nama']);
        $user->bidang = trim($row['bidang'] ?? '');

        // Pembuatan email dummy jika kosong
        $user->email  = !empty($row['email'])
                        ? strtolower(trim($row['email']))
                        : $nipAsli . '@pln.com';

        $user->no_hp  = !empty($row['no_wa']) ? trim($row['no_wa']) : null;

        // Set default password jika user baru
        if (!$user->exists) {
            $user->password = bcrypt('password123');
        }

        $user->save();

        // Assign role 'pegawai'
        if (!$user->hasAnyRole(['admin_k3', 'dokter', 'pegawai'])) {
            $user->assignRole('pegawai');
        }

        $this->rowCount++;
        return $user;
    }

    public function rules(): array
    {
        return [
            'nip'   => ['required', 'string', 'max:20'],
            'nama'  => ['required', 'string', 'max:255'],
            'bidang'=> ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_wa' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nip.required'  => 'Kolom NIP wajib diisi (baris :attribute)',
            'nama.required' => 'Kolom Nama wajib diisi (baris :attribute)',
            'email.email'   => 'Format email tidak valid (baris :attribute)',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function onError(Throwable $e): void
    {
        \Log::warning('Import error: ' . $e->getMessage());
    }
}
