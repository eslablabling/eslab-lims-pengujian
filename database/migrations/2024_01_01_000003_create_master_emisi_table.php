<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_emisi', function (Blueprint $table) {
            $table->id();
            $table->text('nama_parameter')->nullable();
            $table->text('parameter')->nullable();
            $table->string('satuan')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('baku_mutu', 15, 4)->nullable();
            $table->text('regulasi')->nullable();
            $table->text('metode')->nullable();
            $table->decimal('koreksi_o2', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_emisi');
    }
};
