<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coc_id')->constrained('coc_emisi')->onDelete('cascade');
            $table->string('sample_id');
            $table->text('description')->nullable();
            $table->string('nama_cerobong')->nullable();
            $table->string('status')->default('Pending');
            $table->string('status_lab')->default('Pending');
            $table->boolean('is_verified')->default(false);
            $table->date('tgl_sampling')->nullable();
            $table->timestamp('tgl_terima_lab')->nullable();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('rework_reason')->nullable();

            // Field / Environmental data
            $table->string('temp_ambien')->nullable();
            $table->string('kelembaban')->nullable();
            $table->string('kec_angin')->nullable();
            $table->string('koordinat')->nullable();
            $table->string('bahan_bakar')->nullable();
            $table->string('catatan_cuaca')->nullable();

            // Gas data
            $table->string('waktu_gas')->nullable();
            $table->string('no_alat_gas')->nullable();
            $table->decimal('temp_gas', 10, 4)->nullable();
            $table->decimal('tekanan_atm', 10, 4)->nullable();

            // Opacity fields
            $table->decimal('jarak_pengamat_awal', 10, 2)->nullable();
            $table->decimal('jarak_pengamat_akhir', 10, 2)->nullable();
            $table->string('arah_pengamat_awal')->nullable();
            $table->string('arah_pengamat_akhir')->nullable();
            $table->string('warna_emisi_awal')->nullable();
            $table->string('warna_emisi_akhir')->nullable();
            $table->string('latar_asap_awal')->nullable();
            $table->string('latar_asap_akhir')->nullable();
            $table->string('kondisi_langit_awal')->nullable();
            $table->string('kondisi_langit_akhir')->nullable();
            $table->decimal('temp_ambien_awal', 10, 2)->nullable();
            $table->decimal('temp_ambien_akhir', 10, 2)->nullable();
            $table->decimal('kelembaban_awal', 10, 2)->nullable();
            $table->decimal('kelembaban_akhir', 10, 2)->nullable();
            $table->decimal('kec_angin_awal', 10, 2)->nullable();
            $table->decimal('kec_angin_akhir', 10, 2)->nullable();
            $table->string('arah_angin_awal')->nullable();
            $table->string('arah_angin_akhir')->nullable();
            $table->text('desc_emisi')->nullable();
            $table->string('opasitas_mulai')->nullable();
            $table->string('opasitas_akhir')->nullable();
            $table->json('opasitas_matrix')->nullable();
            $table->decimal('opasitas_avg', 10, 2)->nullable();
            $table->string('opasitas_ket_1')->nullable();
            $table->string('opasitas_ket_2')->nullable();
            $table->string('opasitas_ket_3')->nullable();
            $table->string('opasitas_ket_4')->nullable();
            $table->string('opasitas_ket_5')->nullable();
            $table->string('opasitas_ket_6')->nullable();

            // JSON fields for regulations and parameters
            $table->json('regulations')->nullable();
            $table->json('parameters')->nullable();

            // QC fields
            $table->decimal('qc_blank_weight', 10, 4)->nullable();
            $table->decimal('qc_dup_weight_1', 10, 4)->nullable();
            $table->decimal('qc_dup_weight_2', 10, 4)->nullable();
            $table->decimal('qc_rpd', 10, 4)->nullable();
            $table->string('qc_status')->nullable();

            $table->timestamps();

            $table->unique(['coc_id', 'sample_id']);
            $table->index('coc_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
