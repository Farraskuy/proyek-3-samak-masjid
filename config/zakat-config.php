<?php

/**
 * Zakat Configuration
 * 
 * Hardcoded zakat types based on Islamic guidelines from:
 * https://www.allianz.co.id/explore/cara-mudah-menghitung-zakat-penghasilan-dan-cara-membayarnya.html
 * 
 * Values can be updated by admin but calculation logic is fixed.
 */

return [
    // Current market prices (update periodically)
    'harga_emas_per_gram' => 1300000,
    'harga_beras_per_kg' => 13500,

    // Nisab thresholds
    'nisab_emas_gram' => 85, // 85 gram emas = nisab zakat maal
    'nisab_perak_gram' => 595, // 595 gram perak
    'nisab_pertanian_kg' => 520, // 520 kg gabah/beras

    // Zakat types configuration
    'types' => [
        'fitrah' => [
            'name' => 'Zakat Fitrah',
            'short_name' => 'Fitrah',
            'description' => 'Zakat yang wajib dikeluarkan setiap Muslim menjelang Idul Fitri. Setara dengan 2,5 kg atau 3,5 liter beras/makanan pokok per jiwa.',
            'rate' => 2.5, // kg beras per jiwa
            'calculation_type' => 'fixed_beras',
            'has_nisab' => false,
            'inputs' => [
                'jumlah_jiwa' => [
                    'label' => 'Jumlah Jiwa',
                    'type' => 'number',
                    'placeholder' => '1',
                    'min' => 1,
                    'required' => true,
                ]
            ],
            'formula' => 'jumlah_jiwa × 2.5 kg × harga_beras_per_kg'
        ],

        'maal' => [
            'name' => 'Zakat Maal (Harta)',
            'short_name' => 'Maal',
            'description' => 'Zakat dari total kekayaan yang dimiliki selama 1 tahun (haul). Wajib jika harta mencapai nisab setara 85 gram emas.',
            'rate' => 0.025, // 2.5%
            'calculation_type' => 'percentage',
            'has_nisab' => true,
            'inputs' => [
                'total_harta' => [
                    'label' => 'Total Harta (Tahunan)',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => true,
                ],
                'hutang' => [
                    'label' => 'Hutang Jatuh Tempo',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => false,
                ]
            ],
            'formula' => '(total_harta - hutang) × 2.5%'
        ],

        'profesi' => [
            'name' => 'Zakat Penghasilan/Profesi',
            'short_name' => 'Profesi',
            'description' => 'Zakat dari penghasilan rutin pekerjaan yang halal. Nisab setara 85 gram emas per tahun (~Rp7 juta/bulan). Kadar zakat 2,5%.',
            'rate' => 0.025,
            'calculation_type' => 'percentage',
            'has_nisab' => true,
            'is_monthly' => true,
            'inputs' => [
                'gaji_bulanan' => [
                    'label' => 'Penghasilan Bulanan',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => true,
                ],
                'bonus' => [
                    'label' => 'Pendapatan Lain (Bonus/THR)',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => false,
                ],
                'pengeluaran_pokok' => [
                    'label' => 'Pengeluaran Kebutuhan Pokok/Hutang',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => false,
                ]
            ],
            'formula' => '(gaji_bulanan + bonus - pengeluaran_pokok) × 2.5%'
        ],

        'emas' => [
            'name' => 'Zakat Emas & Perak',
            'short_name' => 'Emas',
            'description' => 'Zakat dari kepemilikan emas/perak yang disimpan selama 1 tahun. Nisab emas 85 gram, perak 595 gram. Kadar 2,5%.',
            'rate' => 0.025,
            'calculation_type' => 'percentage_weight',
            'has_nisab' => true,
            'inputs' => [
                'berat_emas' => [
                    'label' => 'Berat Emas (Gram)',
                    'type' => 'number',
                    'placeholder' => '0',
                    'step' => '0.01',
                    'required' => true,
                ]
            ],
            'formula' => 'berat_emas × harga_emas × 2.5%'
        ],

        'tabungan' => [
            'name' => 'Zakat Tabungan',
            'short_name' => 'Tabungan',
            'description' => 'Zakat dari tabungan yang tersimpan selama 1 tahun dan mencapai nisab (setara 85 gram emas). Kadar 2,5%.',
            'rate' => 0.025,
            'calculation_type' => 'percentage',
            'has_nisab' => true,
            'inputs' => [
                'saldo_tabungan' => [
                    'label' => 'Saldo Tabungan',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => true,
                ],
                'bunga' => [
                    'label' => 'Bunga (Jika Ada)',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => false,
                    'note' => 'Bunga riba tidak wajib dizakati, tapi harus dikeluarkan'
                ]
            ],
            'formula' => '(saldo_tabungan + bunga) × 2.5%'
        ],

        'pertanian' => [
            'name' => 'Zakat Pertanian',
            'short_name' => 'Pertanian',
            'description' => 'Zakat dari hasil panen. Nisab 520 kg gabah. Kadar 5% (irigasi berbayar) atau 10% (tadah hujan).',
            'rate_irigasi' => 0.05, // 5%
            'rate_tadah_hujan' => 0.10, // 10%
            'calculation_type' => 'percentage_agriculture',
            'has_nisab' => true,
            'inputs' => [
                'hasil_panen' => [
                    'label' => 'Nilai Hasil Panen',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => true,
                ],
                'is_irigasi' => [
                    'label' => 'Menggunakan Irigasi/Berbiaya',
                    'type' => 'checkbox',
                    'note' => 'Centang jika menggunakan pompa/irigasi berbayar (5%). Jika tidak = tadah hujan (10%).',
                    'required' => false,
                ]
            ],
            'formula' => 'hasil_panen × (5% jika irigasi, 10% jika tadah hujan)'
        ],

        'rikaz' => [
            'name' => 'Zakat Rikaz (Barang Temuan)',
            'short_name' => 'Rikaz',
            'description' => 'Zakat dari harta karun atau barang temuan bernilai. Kadar 20% tanpa nisab.',
            'rate' => 0.20, // 20%
            'calculation_type' => 'percentage',
            'has_nisab' => false,
            'inputs' => [
                'nilai_temuan' => [
                    'label' => 'Nilai Barang Temuan',
                    'type' => 'money',
                    'placeholder' => '0',
                    'required' => true,
                ]
            ],
            'formula' => 'nilai_temuan × 20%'
        ],
    ],

    // 8 Asnaf (penerima zakat)
    'asnaf' => [
        'fakir' => 'Orang yang hampir tidak memiliki apa-apa',
        'miskin' => 'Orang yang memiliki harta tapi tidak mencukupi',
        'amil' => 'Pengelola/pengumpul zakat',
        'muallaf' => 'Orang yang baru masuk Islam',
        'riqab' => 'Hamba sahaya yang ingin memerdekakan diri',
        'gharimin' => 'Orang yang terlilit hutang',
        'fisabilillah' => 'Orang yang berjuang di jalan Allah',
        'ibnu_sabil' => 'Musafir yang kehabisan bekal',
    ]
];
