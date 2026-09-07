<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_peralatan', function (Blueprint $table) {
            $table->id();
            $table->string('no_urut', 20)->nullable();
            $table->string('yymm', 10)->nullable();
            $table->string('no_inventaris', 100)->unique();
            $table->string('nama_alat');
            $table->string('merek_brand', 150)->nullable();
            $table->string('type_model', 150)->nullable();
            $table->string('no_seri', 150)->nullable();
            $table->string('rentang_akurasi')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->date('tgl_kalibrasi')->nullable();
            $table->string('periode_kalibrasi', 50)->default('1 tahun');
            $table->date('jadwal_kalibrasi')->nullable();
            $table->string('lembaga_kalibrasi', 150)->nullable();
            $table->string('kondisi', 50)->default('Baik');
            $table->text('sertifikat_url')->nullable();
            $table->boolean('box_pengaman_default')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('no_inventaris');
            $table->index('nama_alat');
            $table->index('jadwal_kalibrasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_peralatan');
    }
};
