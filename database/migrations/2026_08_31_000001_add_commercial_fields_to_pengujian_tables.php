<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengujian_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pengujian_orders', 'biaya_sampling')) {
                $table->decimal('biaya_sampling', 15, 2)->default(0.00)->after('ppn_persen');
            }
            if (!Schema::hasColumn('pengujian_orders', 'biaya_mop_demop')) {
                $table->decimal('biaya_mop_demop', 15, 2)->default(0.00)->after('biaya_sampling');
            }
            if (!Schema::hasColumn('pengujian_orders', 'diskon')) {
                $table->decimal('diskon', 15, 2)->default(0.00)->after('biaya_mop_demop');
            }
            if (!Schema::hasColumn('pengujian_orders', 'jenis_usaha')) {
                $table->string('jenis_usaha')->nullable()->after('tipe_pekerjaan');
            }
            if (!Schema::hasColumn('pengujian_orders', 'tat_days')) {
                $table->integer('tat_days')->default(14)->after('top_days');
            }
        });

        Schema::table('pengujian_items', function (Blueprint $table) {
            if (!Schema::hasColumn('pengujian_items', 'sample_id')) {
                $table->string('sample_id')->nullable()->after('pengujian_order_id');
            }
            if (!Schema::hasColumn('pengujian_items', 'regulasi')) {
                $table->string('regulasi')->nullable()->after('nama_titik_uji');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengujian_orders', function (Blueprint $table) {
            $table->dropColumn(['biaya_sampling', 'biaya_mop_demop', 'diskon', 'jenis_usaha', 'tat_days']);
        });
        Schema::table('pengujian_items', function (Blueprint $table) {
            $table->dropColumn(['sample_id', 'regulasi']);
        });
    }
};
