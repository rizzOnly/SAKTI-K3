<?php

namespace App\Imports;

use App\Models\KlinikAlat;
use Maatwebsite\Excel\Concerns\{
    ToModel, WithHeadingRow, WithValidation,
    SkipsOnError, SkipsErrors, SkipsEmptyRows
};
use Maatwebsite\Excel\Concerns\Importable;
use Throwable;

class KlinikAlatsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsEmptyRows
{
    use Importable, SkipsErrors;

    private int $rowCount = 0;

    public function model(array $row): ?KlinikAlat
    {
        // Skip if nama_barang is empty
        if (empty($row['nama_barang'])) {
            return null;
        }

        $namaBarang = trim($row['nama_barang']);

        // Cek apakah nama_barang sudah ada (unique by name) → update, belum ada → create
        $alat = KlinikAlat::firstOrNew(['nama_barang' => $namaBarang]);

        $alat->satuan            = trim($row['satuan'] ?? '');
        $alat->stok              = isset($row['stok']) ? (int) $row['stok'] : 1;

        // Handle tgl_kalibrasi_terakhir
        if (!empty($row['tgl_kalibrasi_terakhir'])) {
            if (is_numeric($row['tgl_kalibrasi_terakhir'])) {
                try {
                    $alat->tgl_kalibrasi_terakhir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_kalibrasi_terakhir']);
                } catch (\Throwable $e) {
                    $alat->tgl_kalibrasi_terakhir = null;
                }
            } else {
                $alat->tgl_kalibrasi_terakhir = strtotime(trim($row['tgl_kalibrasi_terakhir']))
                    ? \Carbon\Carbon::parse(trim($row['tgl_kalibrasi_terakhir']))->startOfDay()
                    : null;
            }
        } else {
            $alat->tgl_kalibrasi_terakhir = null;
        }

        // Handle tgl_kalibrasi_ulang
        if (!empty($row['tgl_kalibrasi_ulang'])) {
            if (is_numeric($row['tgl_kalibrasi_ulang'])) {
                try {
                    $alat->tgl_kalibrasi_ulang = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_kalibrasi_ulang']);
                } catch (\Throwable $e) {
                    $alat->tgl_kalibrasi_ulang = null;
                }
            } else {
                $alat->tgl_kalibrasi_ulang = strtotime(trim($row['tgl_kalibrasi_ulang']))
                    ? \Carbon\Carbon::parse(trim($row['tgl_kalibrasi_ulang']))->startOfDay()
                    : null;
            }
        } else {
            $alat->tgl_kalibrasi_ulang = null;
        }

        $alat->save();
        $this->rowCount++;

        return $alat;
    }

    public function rules(): array
    {
        return [
            'nama_barang'          => ['required', 'string', 'max:255'],
            'satuan'               => ['required', 'string', 'max:20'],
            'stok'                 => ['required', 'integer', 'min:0'],
            'tgl_kalibrasi_terakhir' => ['nullable', 'date'],
            'tgl_kalibrasi_ulang'    => ['nullable', 'date'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nama_barang.required' => 'Kolom Nama Alat wajib diisi (baris :attribute)',
            'satuan.required'      => 'Kolom Satuan wajib diisi (baris :attribute)',
            'stok.required'        => 'Kolom Stok wajib diisi (baris :attribute)',
            'stok.integer'         => 'Kolom Stok harus angka (baris :attribute)',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function onError(Throwable $e): void
    {
        \Log::warning('Alat Medis Import error: ' . $e->getMessage());
    }
}
