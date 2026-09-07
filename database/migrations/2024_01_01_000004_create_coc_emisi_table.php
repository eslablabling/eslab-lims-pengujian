<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coc_emisi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_coc')->unique();
            $table->string('company_name');
            $table->string('alamat_perusahaan')->nullable();
            $table->string('company_address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('email_coa')->nullable();
            $table->date('sampling_date')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->integer('tat_days')->nullable();
            $table->string('tat_requested')->nullable();
            $table->string('nomor_qt')->nullable();
            $table->string('qt_no')->nullable();
            $table->string('jenis_usaha')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('Draft');
            $table->string('status_sampling')->nullable();
            $table->string('sampling_officer')->nullable();
            $table->string('sampling_location')->nullable();
            $table->json('samples_data')->nullable();
            // GIS
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('lokasi_kota')->nullable();
            // COA
            $table->text('scanned_coa_url')->nullable();
            // Created by
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('company_name');
            $table->index('sampling_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coc_emisi');
    }
};
