<?php

namespace App\Imports;

use App\Models\ApdItem;
use Maatwebsite\Excel\Concerns\{
    ToModel, WithHeadingRow, WithValidation,
    SkipsOnError, SkipsErrors, SkipsEmptyRows
};
use Maatwebsite\Excel\Concerns\Importable;
use Throwable;

class ApdItemsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsEmptyRows
{
    use Importable, SkipsErrors;

    private int $rowCount = 0;

    public function model(array $row): ?ApdItem
    {
        // Skip if kode_barang is empty
        if (empty($row['kode_barang'])) {
            return null;
        }

        $kodeBarang = trim($row['kode_barang']);

        // Cek apakah kode_barang sudah ada → update, belum ada → create
        $item = ApdItem::firstOrNew(['kode_barang' => $kodeBarang]);

        $item->nama_barang = trim($row['nama_barang'] ?? '');
        $item->satuan      = trim($row['satuan'] ?? '');
        $item->merk        = trim($row['merk'] ?? null);
        $item->kondisi     = trim($row['kondisi'] ?? 'baik');
        $item->stok        = (int) ($row['stok'] ?? 0);
        $item->min_stok    = isset($row['min_stok']) ? (int) $row['min_stok'] : 5;
        $item->is_consumable = isset($row['is_consumable']) ? (bool) $row['is_consumable'] : true;

        // Handle expiry date
        if (!empty($row['exp_date'])) {
            if (is_numeric($row['exp_date'])) {
                // Excel date number (e.g., 45454)
                try {
                    $item->exp_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['exp_date']);
                } catch (\Throwable $e) {
                    $item->exp_date = null;
                }
            } else {
                $item->exp_date = strtotime(trim($row['exp_date']))
                    ? \Carbon\Carbon::parse(trim($row['exp_date']))->startOfDay()
                    : null;
            }
        } else {
            $item->exp_date = null;
        }

        // Handle image_path (optional, typically left empty on bulk import)
        if (!empty($row['image_path'])) {
            $item->image_path = trim($row['image_path']);
        }

        $item->save();
        $this->rowCount++;

        return $item;
    }

    public function rules(): array
    {
        return [
            'kode_barang' => ['required', 'string', 'max:50'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan'      => ['required', 'string', 'max:20'],
            'merk'        => ['nullable', 'string', 'max:100'],
            'kondisi'     => ['required', 'in:baik,rusak,expired'],
            'stok'        => ['required', 'integer', 'min:0'],
            'min_stok'    => ['required', 'integer', 'min:0'],
            'is_consumable'=> ['sometimes', 'boolean'],
            'exp_date'    => ['nullable', 'date'],
            'image_path'  => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'kode_barang.required' => 'Kolom Kode Barang wajib diisi (baris :attribute)',
            'nama_barang.required' => 'Kolom Nama Barang wajib diisi (baris :attribute)',
            'satuan.required'      => 'Kolom Satuan wajib diisi (baris :attribute)',
            'kondisi.required'     => 'Kolom Kondisi wajib diisi (baris :attribute)',
            'kondisi.in'           => 'Kolom Kondisi harus: baik, rusak, atau expired (baris :attribute)',
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
        \Log::warning('APD Import error: ' . $e->getMessage());
    }
}
