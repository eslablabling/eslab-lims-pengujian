<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('kalibrasi:sync-hints-and-wipe', function () {
    $this->info('1. Mengamankan master hint alat dan pelanggan...');
    $alats = \App\Models\KalibrasiAlat::all();
    \App\Http\Controllers\Kalibrasi\KalibrasiPermintaanController::autoCaptureHints([], $alats);

    $this->info('2. Membersihkan data transaksi quotation...');
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    \Illuminate\Support\Facades\DB::table('kalibrasi_sertifikats')->truncate();
    \Illuminate\Support\Facades\DB::table('kalibrasi_evaluasis')->truncate();
    \Illuminate\Support\Facades\DB::table('kalibrasi_inputs')->truncate();
    \Illuminate\Support\Facades\DB::table('kalibrasi_alats')->truncate();
    \Illuminate\Support\Facades\DB::table('kalibrasi_orders')->truncate();
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $hints = \App\Models\KalibrasiAlatHint::count();
    $custs = \App\Models\KalibrasiCustomer::count();
    $this->info("SUKSES! Master Hint Tersimpan: {$hints} Alat, {$custs} Customer. Data Quotation Bersih 0.");
})->purpose('Mengamankan master hint dan membersihkan transaksi quotation kalibrasi');

