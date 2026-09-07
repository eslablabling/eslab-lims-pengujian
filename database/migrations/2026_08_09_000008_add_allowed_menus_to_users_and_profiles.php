<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'allowed_menus_json')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('allowed_menus_json')->nullable()->after('can_view_harga');
                $table->boolean('is_active')->default(true)->after('allowed_menus_json');
            });
        }

        if (!Schema::hasColumn('profiles', 'allowed_menus_json')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->text('allowed_menus_json')->nullable()->after('can_view_harga');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'allowed_menus_json')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['allowed_menus_json', 'is_active']);
            });
        }

        if (Schema::hasColumn('profiles', 'allowed_menus_json')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->dropColumn('allowed_menus_json');
            });
        }
    }
};
