<?php

return [

    // Mapping of APD type keywords to their inspection criteria
    // These keywords will be matched against the apd_item's nama_barang
    'criteria_mapping' => [
        'helm' => [
            'label' => 'Helm',
            'criteria' => [
                ['nama' => 'Tali Helm (Chinstrap)', 'key' => 'tali_helm'],
                ['nama' => 'Shell/Runcing Tidak Retak', 'key' => 'shell_baik'],
                ['nama' => 'Strap Dalam (Sweatband)', 'key' => 'strap_dalam'],
                ['nama' => 'Ventilasi Tidak Tersumbat', 'key' => 'ventilasi'],
                ['nama' => 'Label/Marking Terlihat', 'key' => 'label'],
            ],
        ],
        'sarung tangan' => [
            'label' => 'Sarung Tangan',
            'criteria' => [
                ['nama' => 'Tidak Ada Sobekan/Retakan', 'key' => 'tanpa_sobek'],
                ['nama' => 'Bahan Tidak Rusak/Keriput', 'key' => 'bahan_baik'],
                ['nama' => 'Karet/Elastisitas Masih Bagus', 'key' => 'elastis'],
                ['nama' => 'Ukuran Sesuai', 'key' => 'ukuran_sesuai'],
                ['nama' => 'Tidak Ada Bau Tidak Sedap', 'key' => 'tidak_bau'],
            ],
        ],
        'masker' => [
            'label' => 'Masker',
            'criteria' => [
                ['nama' => 'Filter Tidak Tersumbat', 'key' => 'filter_bersih'],
                ['nama' => 'Elastisitas/Tali Masih Bagus', 'key' => 'elastis'],
                ['nama' => 'Bentuk Tidak Terdistorsi', 'key' => 'bentuk_baik'],
                ['nama' => 'Tidak Ada Luka/Robek', 'key' => 'tanpa_robek'],
                ['nama' => 'Expire Date Masih Berlaku', 'key' => 'belum_expired'],
            ],
        ],
        'sepatu' => [
            'label' => 'Sepatu Safety',
            'criteria' => [
                ['nama' => 'Sol/Takik Tidak Terlaluaus', 'key' => 'sol_baik'],
                ['nama' => 'Atas (Upper) Tidak Retak', 'key' => 'upper_baik'],
                ['nama' => 'Tali/Resleting Masih Berfungsi', 'key' => 'tali_resleting'],
                ['nama' => 'Dilandasi (Insole) Tidak Rusak', 'key' => 'insol_baik'],
                ['nama' => 'Safety Toe Masih Solid', 'key' => 'safety_toe'],
            ],
        ],
        'rompi' => [
            'label' => 'Rompi Safety',
            'criteria' => [
                ['nama' => 'Tidak Ada Sobekan/Robekan', 'key' => 'tanpa_sobek'],
                ['nama' => 'Reflektor/Lining Masih Terlihat', 'key' => 'reflektor_baik'],
                ['nama' => 'Kancing/Velcro Masih Bernyala', 'key' => 'kancing_baik'],
                ['nama' => 'Bahan Tidak Menyempit', 'key' => 'bahan_nyaman'],
                ['nama' => 'Tidak Ada Cairan/Bau', 'key' => 'bersih'],
            ],
        ],
        'kacamata' => [
            'label' => 'Kacamata Pelindung',
            'criteria' => [
                ['nama' => 'Lensa Tidak Retak/Berkabur', 'key' => 'lensa_baik'],
                ['nama' => 'Frame/Omong Kokoh', 'key' => 'frame_kokoh'],
                ['nama' => 'Side Shield/Tali Utuh', 'key' => 'sideshield'],
                ['nama' => 'Tidak Ada Embun/ Bergerak', 'key' => 'tidak_berbintik'],
                ['nama' => 'Ganti Jika Anti Fog Rusak', 'key' => 'anti_fog'],
            ],
        ],
        'ear plug' => [
            'label' => 'Ear Plug',
            'criteria' => [
                ['nama' => 'Bentuk Tidak Usang', 'key' => 'bentuk_baik'],
                ['nama' => 'Material Tidak Rusak', 'key' => 'material_baik'],
                ['nama' => 'Kebersihan Terjaga', 'key' => 'bersih'],
                ['nama' => 'Tidak Ada Bau Tidak Sedap', 'key' => 'tidak_bau'],
                ['nama' => 'Packaging/Label Masih Ada', 'key' => 'label_ada'],
            ],
        ],
    ],

    /**
     * Get inspection criteria for a given APD item based on its name
     */
    'get_criteria_for_item' => function (string $apdName): array {
        $config = config('apd_inspection_criteria.criteria_mapping', []);
        $apdName = strtolower($apdName);

        foreach ($config as $keyword => $mapping) {
            if (str_contains($apdName, $keyword)) {
                return $mapping['criteria'];
            }
        }

        // Default generic criteria if no match found
        return [
            ['nama' => 'Fungsi Utuh/Normal', 'key' => 'fungsi_baik'],
            ['nama' => 'Tidak Ada Kerusakan Fisik', 'key' => 'tanpa_rusak'],
            ['nama' => 'Bahasa Tersedia (jika ada)', 'key' => 'label_ada'],
            ['nama' => 'Kebersihan Terjaga', 'key' => 'bersih'],
            ['nama' => 'Tidak Ada Bau Tidak Sedap', 'key' => 'tidak_bau'],
        ];
    },

];
