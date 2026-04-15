<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{SurveyQuestion, SurveyOption};

class SurveyQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $soals = [
            [
                'pertanyaan' => 'Pada saat bekerja di ketinggian 1,8 meter atau lebih, APD apa yang WAJIB digunakan?',
                'urutan'     => 1,
                'options'    => [
                    ['teks_opsi' => 'Helm Safety', 'is_benar' => false],
                    ['teks_opsi' => 'Full Body Harness (Safety Harness)', 'is_benar' => true],
                    ['teks_opsi' => 'Sepatu Safety', 'is_benar' => false],
                    ['teks_opsi' => 'Rompi Safety', 'is_benar' => false],
                ],
            ],
            [
                'pertanyaan' => 'Apa yang harus dilakukan sebelum memulai pekerjaan berbahaya di area PLN?',
                'urutan'     => 2,
                'options'    => [
                    ['teks_opsi' => 'Langsung mulai bekerja', 'is_benar' => false],
                    ['teks_opsi' => 'Meminta izin dari mandor', 'is_benar' => false],
                    ['teks_opsi' => 'Mengisi Work Permit / Surat Izin Kerja', 'is_benar' => true],
                    ['teks_opsi' => 'Menelepon keluarga terlebih dahulu', 'is_benar' => false],
                ],
            ],
            [
                'pertanyaan' => 'Jika menemukan kondisi tidak aman (unsafe condition) di area kerja, apa tindakan yang tepat?',
                'urutan'     => 3,
                'options'    => [
                    ['teks_opsi' => 'Abaikan dan terus bekerja', 'is_benar' => false],
                    ['teks_opsi' => 'Segera laporkan ke pengawas/petugas K3', 'is_benar' => true],
                    ['teks_opsi' => 'Foto dan unggah ke media sosial', 'is_benar' => false],
                    ['teks_opsi' => 'Tangani sendiri tanpa melapor', 'is_benar' => false],
                ],
            ],
            [
                'pertanyaan' => 'Berapakah jumlah Safety Golden Rules yang wajib dipatuhi oleh seluruh pekerja di area PLN?',
                'urutan'     => 4,
                'options'    => [
                    ['teks_opsi' => '3 Rules', 'is_benar' => false],
                    ['teks_opsi' => '5 Rules', 'is_benar' => false],
                    ['teks_opsi' => '8 Rules', 'is_benar' => false],
                    ['teks_opsi' => '10 Rules', 'is_benar' => true],
                ],
            ],
            [
                'pertanyaan' => 'Saat bekerja di ruang terbatas (confined space), langkah pertama yang wajib dilakukan adalah?',
                'urutan'     => 5,
                'options'    => [
                    ['teks_opsi' => 'Langsung masuk dengan perlengkapan standar', 'is_benar' => false],
                    ['teks_opsi' => 'Pengujian atmosfer / gas test terlebih dahulu', 'is_benar' => true],
                    ['teks_opsi' => 'Menyalakan lampu di dalam ruangan', 'is_benar' => false],
                    ['teks_opsi' => 'Membawa minuman untuk persediaan', 'is_benar' => false],
                ],
            ],
        ];

        foreach ($soals as $soal) {
            $q = SurveyQuestion::create([
                'pertanyaan' => $soal['pertanyaan'],
                'urutan'     => $soal['urutan'],
                'is_active'  => true,
            ]);

            foreach ($soal['options'] as $i => $opt) {
                SurveyOption::create([
                    'survey_question_id' => $q->id,
                    'teks_opsi'          => $opt['teks_opsi'],
                    'is_benar'           => $opt['is_benar'],
                    'urutan'             => $i + 1,
                ]);
            }
        }
    }
}
