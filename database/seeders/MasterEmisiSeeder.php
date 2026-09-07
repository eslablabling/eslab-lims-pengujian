<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterEmisi;

class MasterEmisiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'regulasi' => 'PermenLHK 11/2021 Lamp I.3 (Minyak)',
                'parameter' => 'Nitrogen Oxide (NOx)',
                'nama_parameter' => 'Nitrogen Oxide (NOx)',
                'satuan' => 'mg/m3',
                'unit' => 'mg/Nm3',
                'baku_mutu' => 2300,
                'metode' => 'IKM-ESP-7.2.15 (NDIR)',
                'koreksi_o2' => 15
            ],
            [
                'regulasi' => 'PERMENLHK 19/2017 LAMP I 5.C',
                'parameter' => 'Particulate',
                'nama_parameter' => 'Particulate',
                'satuan' => 'mg/m3',
                'unit' => 'mg/Nm3',
                'baku_mutu' => 60,
                'metode' => 'SNI 7117-21:2021',
                'koreksi_o2' => 7
            ],
            [
                'regulasi' => 'PERMENLH 13/2009 LAMP I C',
                'parameter' => 'Sulfur Dioxide (SO2)',
                'nama_parameter' => 'Sulfur Dioxide (SO2)',
                'satuan' => 'mg/m3',
                'unit' => 'mg/Nm3',
                'baku_mutu' => 1200,
                'metode' => 'IKM-ESP-7.2.18 (NDIR)',
                'koreksi_o2' => 5
            ],
            [
                'regulasi' => 'PERMENLHK 19/2017 LAMP IV.2 (>570)',
                'parameter' => 'Carbon Monoxide (CO)',
                'nama_parameter' => 'Carbon Monoxide (CO)',
                'satuan' => 'mg/m3',
                'unit' => 'mg/Nm3',
                'baku_mutu' => 600,
                'metode' => 'IKM-ESP-7.2.8 (NDIR)',
                'koreksi_o2' => 13
            ],
            [
                'regulasi' => 'KepmenLH 13/1995 Lamp V B',
                'parameter' => 'Sulfur Dioxide (SO2)',
                'nama_parameter' => 'Sulfur Dioxide (SO2)',
                'satuan' => 'mg/m3',
                'unit' => 'mg/Nm3',
                'baku_mutu' => 800,
                'metode' => 'IKM-ESP-7.2.17',
                'koreksi_o2' => null
            ],
            [
                'regulasi' => 'SUB-ROUTINE METODE',
                'parameter' => 'Carbon Dioxide (CO2)',
                'nama_parameter' => 'Carbon Dioxide (CO2)',
                'satuan' => '%',
                'unit' => 'mg/Nm3',
                'baku_mutu' => null,
                'metode' => 'IKM-ESP-7.2.9 (NDIR)',
                'koreksi_o2' => null
            ],
            [
                'regulasi' => 'PERMENLHK 19/2017 LAMP I 1.C',
                'parameter' => 'Hg',
                'nama_parameter' => 'Hg',
                'satuan' => 'mg/m3',
                'unit' => 'mg/Nm3',
                'baku_mutu' => 0.2,
                'metode' => 'subcont',
                'koreksi_o2' => 7
            ]
        ];

        foreach ($items as $item) {
            MasterEmisi::create($item);
        }
    }
}
