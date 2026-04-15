<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin K3
        $adminK3 = User::create([
            'nip'      => '1990001001',
            'name'     => 'Admin K3 PLN',
            'email'    => 'arissaputra012345@gmail.com',
            'password' => bcrypt('password123'),
            'bidang'   => 'K3 & Lingkungan',
            'no_hp'    => '089695466685',
        ]);
        $adminK3->assignRole('admin_k3');

        // Dokter
        $dokter = User::create([
            'nip'      => '1990001002',
            'name'     => 'dr. Budi Santoso',
            'email'    => 'wattsonkyot@gmail.com',
            'password' => bcrypt('password123'),
            'bidang'   => 'Klinik',
            'no_hp'    => '089695466685',
        ]);
        $dokter->assignRole('dokter');

        // Pegawai (10 orang)
        $bidangList = ['Produksi', 'Pemeliharaan', 'Operasi'];
        for ($i = 1; $i <= 10; $i++) {
            $pegawai = User::create([
                'nip'      => '199000200' . $i,
                'name'     => 'Pegawai ' . $i,
                'email'    => 'pegawai' . $i . '@pln-sengkang.com',
                'password' => bcrypt('password123'),
                'bidang'   => $bidangList[($i - 1) % 3],
                'no_hp'    => '0812345' . str_pad($i, 5, '0', STR_PAD_LEFT),
            ]);
            $pegawai->assignRole('pegawai');
        }
    }
}
