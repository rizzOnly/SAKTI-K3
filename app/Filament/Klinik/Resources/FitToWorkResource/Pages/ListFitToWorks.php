<?php
namespace App\Filament\Klinik\Resources\FitToWorkResource\Pages;

use App\Filament\Klinik\Resources\FitToWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\FitToWork;

class ListFitToWorks extends ListRecords
{
    protected static string $resource = FitToWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ── TOMBOL EXPORT EXCEL (.xls) ──
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->action(function () {
                    $fileName = 'Laporan_Fit_To_Work_' . date('Y-m-d') . '.xls';

                    $headers = [
                        'Content-Type'        => 'application/vnd.ms-excel; charset=utf-8',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ];

                    $callback = function() {
                        // Format HTML Table untuk dibaca oleh Excel
                        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
                        echo '<head><meta charset="utf-8"></head>';
                        echo '<body>';
                        echo '<table border="1" cellpadding="5">';

                        // Baris Judul (Header) dengan Warna Biru Khas PLN
                        echo '<tr style="background-color: #003D7C; color: #ffffff; font-weight: bold; text-align: center;">';
                        echo '<th>No</th>';
                        echo '<th>Tipe</th>';
                        echo '<th>Nama Perusahaan</th>';
                        echo '<th>Nama Pekerjaan</th>';
                        echo '<th>Tgl Mulai</th>';
                        echo '<th>Tgl Selesai</th>';
                        echo '<th>Nama Pekerja</th>';
                        echo '<th>L/P</th>';
                        echo '<th>Status Fit</th>';
                        echo '<th>Tgl Diperiksa</th>';
                        echo '<th>Dokter Pemeriksa</th>';
                        echo '<th>Catatan Dokter</th>';
                        echo '</tr>';

                        // Ambil semua data
                        $submissions = FitToWork::with('pekerjas')->get();

                        $rowNum = 1;
                        foreach ($submissions as $submission) {
                            $tipe = strtoupper($submission->tipe);
                            $perusahaan = $submission->nama_perusahaan ?: 'Internal PLN';
                            $pekerjaan = $submission->nama_pekerjaan;
                            $tglMulai = $submission->tanggal_mulai ? $submission->tanggal_mulai->format('d/m/Y') : '-';
                            $tglSelesai = $submission->tanggal_selesai ? $submission->tanggal_selesai->format('d/m/Y') : '-';

                            if ($submission->pekerjas->isEmpty()) {
                                // Jika perusahaan belum mendaftarkan pekerja
                                echo '<tr>';
                                echo '<td style="text-align:center;">'.$rowNum++.'</td>';
                                echo '<td>'.$tipe.'</td>';
                                echo '<td>'.$perusahaan.'</td>';
                                echo '<td>'.$pekerjaan.'</td>';
                                echo '<td style="text-align:center;">'.$tglMulai.'</td>';
                                echo '<td style="text-align:center;">'.$tglSelesai.'</td>';
                                echo '<td colspan="6" style="text-align:center; font-style:italic;">Belum ada pekerja terdaftar</td>';
                                echo '</tr>';
                            } else {
                                // Jika ada pekerja, keluarkan daftar namanya satu per satu
                                foreach ($submission->pekerjas as $pekerja) {
                                    $statusFit = str_replace(['✅', '❌'], '', $pekerja->status_label);

                                    // Logika Pewarnaan Kolom Status di Excel
                                    $warnaStatus = '';
                                    if ($pekerja->status === 'fit') {
                                        $warnaStatus = '#dcfce7'; // Hijau muda
                                    } elseif ($pekerja->status === 'tidak_fit') {
                                        $warnaStatus = '#fee2e2'; // Merah muda
                                    } else {
                                        $warnaStatus = '#fef9c3'; // Kuning
                                    }

                                    echo '<tr>';
                                    echo '<td style="text-align:center;">'.$rowNum++.'</td>';
                                    echo '<td>'.$tipe.'</td>';
                                    echo '<td>'.$perusahaan.'</td>';
                                    echo '<td>'.$pekerjaan.'</td>';
                                    echo '<td style="text-align:center;">'.$tglMulai.'</td>';
                                    echo '<td style="text-align:center;">'.$tglSelesai.'</td>';
                                    echo '<td>'.$pekerja->nama.'</td>';
                                    echo '<td style="text-align:center;">'.$pekerja->jenis_kelamin_label.'</td>';
                                    // Kolom status diberi warna background
                                    echo '<td style="background-color: '.$warnaStatus.'; text-align:center;">'.trim($statusFit).'</td>';
                                    echo '<td style="text-align:center;">'.($pekerja->tanggal_periksa ? $pekerja->tanggal_periksa->format('d/m/Y') : '-').'</td>';
                                    echo '<td>'.($pekerja->dokter_nama ?? '-').'</td>';
                                    echo '<td>'.($pekerja->catatan_dokter ?? '-').'</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                        echo '</table>';
                        echo '</body></html>';
                    };

                    return response()->stream($callback, 200, $headers);
                }),

            Actions\CreateAction::make(),
        ];
    }
}
