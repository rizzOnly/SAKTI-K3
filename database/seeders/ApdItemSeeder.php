<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApdItem;
use Carbon\Carbon;

class ApdItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'kode_barang'   => 'APD0001',
                'nama_barang'   => 'Helm Safety',
                'satuan'        => 'Buah',
                'merk'          => '3M',
                'kondisi'       => 'baik',
                'stok'          => 50,
                'min_stok'      => 10,
                'is_consumable' => false,
                'lokasi_gudang' => 'Rak A1',
            ],
            [
                'kode_barang'   => 'APD0002',
                'nama_barang'   => 'Sarung Tangan Karet',
                'satuan'        => 'Pasang',
                'merk'          => 'Ansell',
                'kondisi'       => 'baik',
                'stok'          => 200,
                'min_stok'      => 50,
                'is_consumable' => true,
                'exp_date'      => Carbon::now()->addMonths(6),
                'lokasi_gudang' => 'Rak A2',
            ],
            [
                'kode_barang'   => 'APD0003',
                'nama_barang'   => 'Masker N95',
                'satuan'        => 'Box',
                'merk'          => '3M',
                'kondisi'       => 'baik',
                'stok'          => 30,
                'min_stok'      => 10,
                'is_consumable' => true,
                'exp_date'      => Carbon::now()->addMonths(12),
                'lokasi_gudang' => 'Rak B1',
            ],
            [
                'kode_barang'   => 'APD0004',
                'nama_barang'   => 'Sepatu Safety',
                'satuan'        => 'Pasang',
                'merk'          => 'Safety Jogger',
                'kondisi'       => 'baik',
                'stok'          => 4,  // Sengaja kritis untuk testing EWS
                'min_stok'      => 5,
                'is_consumable' => false,
                'lokasi_gudang' => 'Rak B2',
            ],
            [
                'kode_barang'   => 'APD0005',
                'nama_barang'   => 'Rompi Safety',
                'satuan'        => 'Buah',
                'merk'          => '-',
                'kondisi'       => 'baik',
                'stok'          => 25,
                'min_stok'      => 5,
                'is_consumable' => false,
                'lokasi_gudang' => 'Rak C1',
            ],
            [
                'kode_barang'   => 'APD0006',
                'nama_barang'   => 'Kacamata Pelindung',
                'satuan'        => 'Buah',
                'merk'          => 'Honeywell',
                'kondisi'       => 'baik',
                'stok'          => 15,
                'min_stok'      => 5,
                'is_consumable' => false,
                'lokasi_gudang' => 'Rak C2',
            ],
            [
                'kode_barang'   => 'APD0007',
                'nama_barang'   => 'Ear Plug',
                'satuan'        => 'Pasang',
                'merk'          => '3M',
                'kondisi'       => 'baik',
                'stok'          => 100,
                'min_stok'      => 20,
                'is_consumable' => true,
                'exp_date'      => Carbon::now()->addDays(20), // Sengaja akan expired untuk testing
                'lokasi_gudang' => 'Rak D1',
            ],
        ];

        foreach ($items as $item) {
            ApdItem::create($item);
        }
    }
}
