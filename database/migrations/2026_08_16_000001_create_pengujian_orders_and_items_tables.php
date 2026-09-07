<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pengujian_orders')) {
            Schema::create('pengujian_orders', function (Blueprint $table) {
                $table->id();
                $table->string('no_order')->unique();
                $table->string('no_quotation')->nullable();
                $table->string('no_po')->nullable();
                $table->string('file_po')->nullable();
                $table->string('nama_pelanggan');
                $table->text('alamat_pelanggan')->nullable();
                $table->string('kontak_person')->nullable();
                $table->string('no_hp')->nullable();
                $table->string('email')->nullable();
                $table->enum('tipe_pekerjaan', ['in_lab', 'on_site'])->default('on_site');
                $table->string('status')->default('Quotation');
                $table->string('status_verifikasi')->default('Menunggu Verifikasi');
                $table->text('alasan_verifikasi')->nullable();
                $table->date('tanggal_masuk')->nullable();
                $table->date('target_selesai')->nullable();
                $table->text('catatan')->nullable();

                // Jadwal & Surat Tugas Sampling
                $table->date('jadwal_sampling')->nullable();
                $table->string('petugas_sampling')->nullable();
                $table->string('surat_tugas_no')->nullable();
                $table->string('lokasi_sampling')->nullable();

                // Finance & Invoicing
                $table->string('no_invoice')->nullable();
                $table->string('status_pembayaran', 50)->default('Belum Lunas');
                $table->decimal('nominal_dibayar', 15, 2)->default(0.00);
                $table->integer('top_days')->default(30);
                $table->string('metode_pembayaran', 100)->nullable();
                $table->text('catatan_pembayaran')->nullable();
                $table->timestamp('tgl_peringatan_terakhir')->nullable();
                $table->boolean('is_ppn')->default(true);
                $table->decimal('ppn_persen', 5, 2)->default(11.00);
                $table->boolean('is_dp')->default(false);
                $table->decimal('dp_persen', 5, 2)->default(50.00);
                $table->decimal('dp_amount', 15, 2)->default(0.00);
                $table->string('no_kwitansi')->nullable();
                $table->string('no_bast')->nullable();
                $table->string('no_tst')->nullable();
                $table->string('faktur_pajak_no')->nullable();
                $table->string('direktur_name')->default('Fany Kusuma Hadi');
                $table->string('lab_manager_name')->default('Ferry Ferdyansyah');
                $table->text('catatan_invoice')->nullable();
                $table->boolean('is_verified_finance')->default(false);
                $table->timestamp('verified_finance_at')->nullable();
                $table->date('tgl_bayar')->nullable();

                // Pengiriman Dokumen
                $table->string('no_resi_pengiriman')->nullable();
                $table->string('kurir_pengiriman')->nullable();
                $table->date('tgl_kirim_dokumen')->nullable();
                $table->string('foto_bukti_kirim')->nullable();
                $table->string('status_pengiriman')->default('Belum Dikirim');

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pengujian_items')) {
            Schema::create('pengujian_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pengujian_order_id')->constrained('pengujian_orders')->onDelete('cascade');
                $table->string('nama_titik_uji');
                $table->string('kategori_uji')->default('Emisi Sumber Tidak Bergerak');
                $table->json('parameter_uji_json')->nullable();
                $table->decimal('harga_satuan', 15, 2)->default(0.00);
                $table->integer('jumlah_titik')->default(1);
                $table->decimal('subtotal', 15, 2)->default(0.00);
                $table->unsignedBigInteger('coc_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengujian_items');
        Schema::dropIfExists('pengujian_orders');
    }
};
