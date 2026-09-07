<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'username')) {
                    $table->string('username')->nullable()->after('name');
                }
                if (!Schema::hasColumn('users', 'role_code')) {
                    $table->string('role_code')->default('staff')->after('role');
                }
                if (!Schema::hasColumn('users', 'can_access_kalibrasi')) {
                    $table->boolean('can_access_kalibrasi')->default(true)->after('role_code');
                }
                if (!Schema::hasColumn('users', 'can_access_pengujian')) {
                    $table->boolean('can_access_pengujian')->default(true)->after('can_access_kalibrasi');
                }
                if (!Schema::hasColumn('users', 'can_access_hris')) {
                    $table->boolean('can_access_hris')->default(true)->after('can_access_pengujian');
                }
                if (!Schema::hasColumn('users', 'can_view_harga')) {
                    $table->boolean('can_view_harga')->default(true)->after('can_access_hris');
                }
            });
        }

        if (Schema::hasTable('profiles')) {
            // Change enum role to string if needed
            Schema::table('profiles', function (Blueprint $table) {
                $table->string('role')->default('sampling')->change();
                if (!Schema::hasColumn('profiles', 'role_code')) {
                    $table->string('role_code')->default('staff')->after('role');
                }
                if (!Schema::hasColumn('profiles', 'can_access_kalibrasi')) {
                    $table->boolean('can_access_kalibrasi')->default(true)->after('role_code');
                }
                if (!Schema::hasColumn('profiles', 'can_access_pengujian')) {
                    $table->boolean('can_access_pengujian')->default(true)->after('can_access_kalibrasi');
                }
                if (!Schema::hasColumn('profiles', 'can_access_hris')) {
                    $table->boolean('can_access_hris')->default(true)->after('can_access_pengujian');
                }
                if (!Schema::hasColumn('profiles', 'can_view_harga')) {
                    $table->boolean('can_view_harga')->default(true)->after('can_access_hris');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['role_code', 'can_access_kalibrasi', 'can_access_pengujian', 'can_access_hris', 'can_view_harga']);
            });
        }
        if (Schema::hasTable('profiles')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->dropColumn(['role_code', 'can_access_kalibrasi', 'can_access_pengujian', 'can_access_hris', 'can_view_harga']);
            });
        }
    }
};
