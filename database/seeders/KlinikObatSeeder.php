<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlinikObat;
use App\Models\KlinikAlat;
use Carbon\Carbon;

class KlinikObatSeeder extends Seeder
{
    public function run(): void
    {
        // Obat-obatan
        $obats = [
            [
                'kode_obat'    => 'OBT001',
                'nama_barang'  => 'Paracetamol 500mg',
                'satuan'       => 'Tablet',
                'stok'         => 500,
                'min_stok'     => 100,
                'tanggal_masuk'=> Carbon::now()->subMonths(2),
                'tanggal_exp'  => Carbon::now()->addYear(),
            ],
            [
                'kode_obat'    => 'OBT002',
                'nama_barang'  => 'Amoxicillin 500mg',
                'satuan'       => 'Kapsul',
                'stok'         => 200,
                'min_stok'     => 50,
                'tanggal_masuk'=> Carbon::now()->subMonth(),
                'tanggal_exp'  => Carbon::now()->addMonths(8),
            ],
            [
                'kode_obat'    => 'OBT003',
                'nama_barang'  => 'Antasida Tablet',
                'satuan'       => 'Tablet',
                'stok'         => 80,
                'min_stok'     => 100, // Sengaja kritis untuk testing
                'tanggal_masuk'=> Carbon::now()->subMonths(3),
                'tanggal_exp'  => Carbon::now()->addDays(25), // Akan expired
            ],
            [
                'kode_obat'    => 'OBT004',
                'nama_barang'  => 'Vitamin C 1000mg',
                'satuan'       => 'Tablet',
                'stok'         => 300,
                'min_stok'     => 50,
                'tanggal_masuk'=> Carbon::now()->subMonth(),
                'tanggal_exp'  => Carbon::now()->addMonths(18),
            ],
            [
                'kode_obat'    => 'OBT005',
                'nama_barang'  => 'Betadine 30ml',
                'satuan'       => 'Botol',
                'stok'         => 20,
                'min_stok'     => 5,
                'tanggal_masuk'=> Carbon::now()->subMonth(),
                'tanggal_exp'  => Carbon::now()->addYear(),
            ],
        ];

        foreach ($obats as $obat) {
            KlinikObat::create($obat);
        }

        // Alat medis
        $alats = [
            [
                'nama_barang'          => 'Tensimeter Digital',
                'satuan'               => 'Unit',
                'stok'                 => 2,
                'tgl_kalibrasi_terakhir' => Carbon::now()->subMonths(6),
                'tgl_kalibrasi_ulang'  => Carbon::now()->addDays(5), // Segera kalibrasi
            ],
            [
                'nama_barang'          => 'Termometer Digital',
                'satuan'               => 'Unit',
                'stok'                 => 3,
                'tgl_kalibrasi_terakhir' => Carbon::now()->subMonths(3),
                'tgl_kalibrasi_ulang'  => Carbon::now()->addMonths(3),
            ],
            [
                'nama_barang'          => 'Stetoskop',
                'satuan'               => 'Unit',
                'stok'                 => 2,
                'tgl_kalibrasi_terakhir' => Carbon::now()->subYear(),
                'tgl_kalibrasi_ulang'  => Carbon::now()->addMonths(6),
            ],
        ];

        foreach ($alats as $alat) {
            KlinikAlat::create($alat);
        }
    }
}
