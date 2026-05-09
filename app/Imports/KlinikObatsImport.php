<?php

namespace App\Imports;

use App\Models\KlinikObat;
use Maatwebsite\Excel\Concerns\{
    ToModel, WithHeadingRow, WithValidation,
    SkipsOnError, SkipsErrors, SkipsEmptyRows
};
use Maatwebsite\Excel\Concerns\Importable;
use Throwable;

class KlinikObatsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsEmptyRows
{
    use Importable, SkipsErrors;

    private int $rowCount = 0;

    public function model(array $row): ?KlinikObat
    {
        // Skip if kode_obat is empty
        if (empty($row['kode_obat'])) {
            return null;
        }

        $kodeObat = trim($row['kode_obat']);

        // Cek apakah kode_obat sudah ada → update, belum ada → create
        $obat = KlinikObat::firstOrNew(['kode_obat' => $kodeObat]);

        $obat->nama_barang    = trim($row['nama_barang'] ?? '');
        $obat->satuan         = trim($row['satuan'] ?? '');
        $obat->stok           = isset($row['stok']) ? (int) $row['stok'] : 0;
        $obat->min_stok       = isset($row['min_stok']) ? (int) $row['min_stok'] : 10;

        // Handle tanggal_masuk
        if (!empty($row['tanggal_masuk'])) {
            if (is_numeric($row['tanggal_masuk'])) {
                try {
                    $obat->tanggal_masuk = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_masuk']);
                } catch (\Throwable $e) {
                    $obat->tanggal_masuk = null;
                }
            } else {
                $obat->tanggal_masuk = strtotime(trim($row['tanggal_masuk']))
                    ? \Carbon\Carbon::parse(trim($row['tanggal_masuk']))->startOfDay()
                    : null;
            }
        } else {
            $obat->tanggal_masuk = null;
        }

        // Handle tanggal_exp (expiry)
        if (!empty($row['tanggal_exp'])) {
            if (is_numeric($row['tanggal_exp'])) {
                try {
                    $obat->tanggal_exp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_exp']);
                } catch (\Throwable $e) {
                    $obat->tanggal_exp = null;
                }
            } else {
                $obat->tanggal_exp = strtotime(trim($row['tanggal_exp']))
                    ? \Carbon\Carbon::parse(trim($row['tanggal_exp']))->startOfDay()
                    : null;
            }
        } else {
            $obat->tanggal_exp = null;
        }

        $obat->save();
        $this->rowCount++;

        return $obat;
    }

    public function rules(): array
    {
        return [
            'kode_obat'   => ['required', 'string', 'max:50'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan'      => ['required', 'string', 'max:20'],
            'stok'        => ['required', 'integer', 'min:0'],
            'min_stok'    => ['required', 'integer', 'min:0'],
            'tanggal_masuk' => ['nullable', 'date'],
            'tanggal_exp'   => ['nullable', 'date'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'kode_obat.required'   => 'Kolom Kode Obat wajib diisi (baris :attribute)',
            'nama_barang.required' => 'Kolom Nama Obat wajib diisi (baris :attribute)',
            'satuan.required'      => 'Kolom Satuan wajib diisi (baris :attribute)',
            'stok.required'        => 'Kolom Stok wajib diisi (baris :attribute)',
            'stok.integer'         => 'Kolom Stok harus angka (baris :attribute)',
            'min_stok.required'    => 'Kolom Minimum Stok wajib diisi (baris :attribute)',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function onError(Throwable $e): void
    {
        \Log::warning('Obat Import error: ' . $e->getMessage());
    }
}
