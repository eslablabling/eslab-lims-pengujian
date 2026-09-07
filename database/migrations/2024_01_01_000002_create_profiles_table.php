<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('full_name')->nullable();
            $table->string('username')->nullable();
            $table->enum('role', ['admin_master', 'manager', 'admin_ts', 'sampling', 'analis', 'client'])->default('sampling');
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->string('plain_password')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status_karyawan')->default('aktif');
            $table->timestamp('last_password_reset')->nullable();
            $table->string('avatar_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
