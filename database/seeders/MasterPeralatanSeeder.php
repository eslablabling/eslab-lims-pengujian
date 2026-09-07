<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterPeralatan;

class MasterPeralatanSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['no_urut' => '002.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/002.1', 'nama_alat' => 'Heating Drying Oven', 'merek_brand' => 'Ari Medical', 'type_model' => 'DHG Series', 'no_seri' => '25100078', 'rentang_akurasi' => '10-200 °C', 'lokasi' => 'Lab Utama', 'tgl_kalibrasi' => '2026-06-26', 'jadwal_kalibrasi' => '2027-06-26', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '003.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/003.1', 'nama_alat' => 'Desikator', 'merek_brand' => '-', 'type_model' => '-', 'no_seri' => '-', 'rentang_akurasi' => '-', 'lokasi' => 'R. Instrumen 1', 'kondisi' => 'Baik'],
            ['no_urut' => '004.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/004.1', 'nama_alat' => 'Spektrofotometer Vis', 'merek_brand' => 'UNICO', 'type_model' => 'S-2150UV', 'no_seri' => 'KA 25022211061', 'rentang_akurasi' => '200 - 1000 nm', 'lokasi' => 'R. Instrumen 2', 'tgl_kalibrasi' => '2026-01-26', 'jadwal_kalibrasi' => '2027-01-26', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '005.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/005.1', 'nama_alat' => 'Lemari Asam', 'merek_brand' => 'Lokal', 'lokasi' => 'Lab Utama', 'tgl_kalibrasi' => '2026-06-26', 'jadwal_kalibrasi' => '2027-06-26', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.1', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'HTC-1', 'rentang_akurasi' => 'Suhu : 0~100°C; Kelembaban : 0~100 %', 'lokasi' => 'R. Instrumen 1', 'tgl_kalibrasi' => '2026-04-01', 'jadwal_kalibrasi' => '2027-04-01', 'lembaga_kalibrasi' => 'LK-106-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.2', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.2', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'HTC-1', 'rentang_akurasi' => 'Suhu : 0~100°C; Kelembaban : 0~100 %', 'lokasi' => 'Lab Utama', 'tgl_kalibrasi' => '2026-06-01', 'jadwal_kalibrasi' => '2027-06-01', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.3', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.3', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'HTC-1', 'rentang_akurasi' => 'Suhu : 0~100°C; Kelembaban : 0~100 %', 'lokasi' => 'R. Office', 'tgl_kalibrasi' => '2026-06-01', 'jadwal_kalibrasi' => '2027-06-01', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.4', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.4', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'HTC-1', 'rentang_akurasi' => 'Suhu : 0~100°C; Kelembaban : 0~100 %', 'lokasi' => 'R. Instrumen 2', 'tgl_kalibrasi' => '2026-06-01', 'jadwal_kalibrasi' => '2027-06-01', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.5', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.5', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'HTC-1', 'rentang_akurasi' => 'Suhu : 0~100°C; Kelembaban : 0~100 %', 'lokasi' => 'Dry Box Filter 1', 'tgl_kalibrasi' => '2026-04-01', 'jadwal_kalibrasi' => '2027-04-01', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.6', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.6', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'HTC-1', 'rentang_akurasi' => 'Suhu : 0~100°C; Kelembaban : 0~100 %', 'lokasi' => 'Dry Box Filter 2', 'tgl_kalibrasi' => '2026-04-01', 'jadwal_kalibrasi' => '2027-04-01', 'lembaga_kalibrasi' => 'LK-106-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.7', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.7', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'HTC-1', 'rentang_akurasi' => 'Suhu : 0~100°C; Kelembaban : 0~100 %', 'lokasi' => 'Desikator', 'tgl_kalibrasi' => '2026-04-01', 'jadwal_kalibrasi' => '2027-04-01', 'lembaga_kalibrasi' => 'LK-106-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '006.8', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/006.8', 'nama_alat' => 'Thermohygrometer', 'type_model' => 'DR Gray', 'rentang_akurasi' => '-50 ~ +70°C', 'lokasi' => 'Showcase Cooler', 'tgl_kalibrasi' => '2026-03-01', 'jadwal_kalibrasi' => '2027-03-01', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '007.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/007.1', 'nama_alat' => 'Hot Plate', 'rentang_akurasi' => '1 °C', 'lokasi' => 'Lab Utama', 'tgl_kalibrasi' => '2026-06-11', 'jadwal_kalibrasi' => '2027-06-11', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '008.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/008.1', 'nama_alat' => 'Gas Analyzer', 'merek_brand' => 'Seitron', 'lokasi' => 'R. Sampling', 'tgl_kalibrasi' => '2026-04-03', 'jadwal_kalibrasi' => '2027-04-03', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '009.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/009.1', 'nama_alat' => 'Opasitas Ringleman', 'merek_brand' => 'Fujis scope', 'type_model' => 'OPIM-FS 102B', 'rentang_akurasi' => '0~100%', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '010.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/010.1', 'nama_alat' => 'Dry Box Filter', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '010.2', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/010.2', 'nama_alat' => 'Dry Box Filter', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '011.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/011.1', 'nama_alat' => 'Cooler Box', 'merek_brand' => 'Lion star', 'type_model' => '35s', 'rentang_akurasi' => '35L', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '012.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/012.1', 'nama_alat' => 'Jangka sorong', 'lokasi' => 'R. Sampling', 'tgl_kalibrasi' => '2026-01-07', 'jadwal_kalibrasi' => '2027-01-07', 'lembaga_kalibrasi' => 'LK-378-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '013.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/013.1', 'nama_alat' => 'Trolley', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '014.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/014.1', 'nama_alat' => 'Neraca Teknis', 'rentang_akurasi' => '0.01 gram', 'lokasi' => 'R. Sampling', 'tgl_kalibrasi' => '2026-01-26', 'jadwal_kalibrasi' => '2027-01-26', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '015.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/015.1', 'nama_alat' => 'Isokinetik Stack Sampler', 'merek_brand' => 'Apex Instruments', 'lokasi' => 'R. Sampling', 'tgl_kalibrasi' => '2026-03-09', 'jadwal_kalibrasi' => '2027-03-09', 'lembaga_kalibrasi' => 'NC-27526-USA', 'kondisi' => 'Baik'],
            ['no_urut' => '016.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/016.1', 'nama_alat' => 'Isokinetik Stack Sampler', 'merek_brand' => 'Polltech Instruments', 'type_model' => 'PEM-SMK10-L', 'no_seri' => '0825', 'rentang_akurasi' => '0.02', 'lokasi' => 'R. Sampling', 'tgl_kalibrasi' => '2025-11-08', 'jadwal_kalibrasi' => '2026-11-07', 'lembaga_kalibrasi' => 'CC-4126', 'kondisi' => 'Baik'],
            ['no_urut' => '017.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/017.1', 'nama_alat' => 'Kabel Roll', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '017.2', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/017.2', 'nama_alat' => 'Kabel Roll', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '017.3', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/017.3', 'nama_alat' => 'Kabel Roll', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '018.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/018.1', 'nama_alat' => 'Tripod', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '019.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/019.1', 'nama_alat' => 'Tatakan', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '020.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/020.1', 'nama_alat' => 'Banner Safety Bekerja diKetinggian', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '021.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/021.1', 'nama_alat' => 'Stack Sampler Low Volume', 'merek_brand' => 'Apex Instruments', 'lokasi' => 'R. Sampling', 'tgl_kalibrasi' => '2026-06-10', 'jadwal_kalibrasi' => '2027-06-10', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '022.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/022.1', 'nama_alat' => 'Tali 25 meter', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '023.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/023.1', 'nama_alat' => 'Fylsheet 3x6', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '024.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/024.1', 'nama_alat' => 'Katrol 1 ton', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '025.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/025.1', 'nama_alat' => 'Terpal 2x4', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '026.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/026.1', 'nama_alat' => 'Gas Analyzer', 'merek_brand' => 'MRU MGA PrimeQ', 'lokasi' => 'R. Sampling', 'tgl_kalibrasi' => '2026-01-05', 'jadwal_kalibrasi' => '2026-04-15', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '009.3', 'yymm' => '2001', 'no_inventaris' => 'EQP/ES/2001/009.3', 'nama_alat' => 'Timbangan Analitik Digital', 'merek_brand' => 'AND', 'type_model' => 'GH-252', 'no_seri' => '15111635', 'rentang_akurasi' => 'Max: 250 gr d: 0.0001 gr', 'lokasi' => 'R. Instrumen 1', 'tgl_kalibrasi' => '2025-06-25', 'jadwal_kalibrasi' => '2026-06-25', 'lembaga_kalibrasi' => 'LK-361-IDN', 'kondisi' => 'Baik'],
            ['no_urut' => '027.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/027.1', 'nama_alat' => 'Panel listrik Portable', 'merek_brand' => 'Lokal', 'lokasi' => 'R. Sampling', 'kondisi' => 'Baik'],
            ['no_urut' => '028.1', 'yymm' => '2601', 'no_inventaris' => 'EQP/ES/PL/2601/028.1', 'nama_alat' => 'Showcase Cooler', 'merek_brand' => 'STEKO', 'type_model' => 'LG-300', 'no_seri' => '0727LSP/QI/06.1-XI/2024', 'rentang_akurasi' => '4 ~ 8°C', 'lokasi' => 'Lab Utama', 'kondisi' => 'Baik'],
        ];

        foreach ($items as $item) {
            MasterPeralatan::updateOrCreate(
                ['no_inventaris' => $item['no_inventaris']],
                array_merge(['periode_kalibrasi' => '1 tahun'], $item)
            );
        }
    }
}
