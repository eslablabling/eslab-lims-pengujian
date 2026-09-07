<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('kalibrasi_active_sessions')) {
            Schema::create('kalibrasi_active_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_id')->index();
                $table->string('username')->nullable();
                $table->string('user_name')->nullable();
                $table->string('role')->nullable();
                $table->string('ip_address')->index();
                $table->text('user_agent')->nullable();
                $table->string('device_type')->default('Desktop'); // Mobile, Desktop, Tablet
                $table->string('current_page_title')->nullable();
                $table->string('current_url')->nullable();
                $table->timestamp('last_activity')->useCurrent();
                $table->timestamps();
            });
        } else {
            Schema::table('kalibrasi_active_sessions', function (Blueprint $table) {
                if (!Schema::hasColumn('kalibrasi_active_sessions', 'current_page_title')) {
                    $table->string('current_page_title')->nullable();
                }
                if (!Schema::hasColumn('kalibrasi_active_sessions', 'current_url')) {
                    $table->string('current_url')->nullable();
                }
            });
        }

        if (!Schema::hasTable('kalibrasi_blocked_ips')) {
            Schema::create('kalibrasi_blocked_ips', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address')->unique();
                $table->string('reason')->nullable();
                $table->string('blocked_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalibrasi_active_sessions');
        Schema::dropIfExists('kalibrasi_blocked_ips');
    }
};
