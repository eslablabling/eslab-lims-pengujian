<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_jalan_peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan', 100)->unique();
            $table->foreignId('coc_id')->nullable()->constrained('coc_emisi')->onDelete('cascade');
            $table->string('nama_pekerjaan')->default('Pengambilan Sampel');
            $table->string('nomor_qt', 100)->nullable();
            $table->string('nomor_coc', 100)->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->string('lokasi_pengerjaan')->nullable();
            $table->string('transportasi', 50)->default('Darat');
            $table->date('tgl_pengerjaan_start')->nullable();
            $table->date('tgl_pengerjaan_end')->nullable();
            $table->json('items_json')->nullable();
            $table->string('teknisi_lab', 150)->nullable();
            $table->string('lab_manager', 150)->default('Fadhel Verdino, S.T.');
            $table->string('diserahkan_oleh', 150)->nullable();
            $table->string('diterima_kembali_oleh', 150)->nullable();
            $table->string('status', 50)->default('Dipinjam');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_jalan_peralatan');
    }
};
