<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sampling_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_account_id')->nullable()->constrained('client_accounts')->onDelete('cascade');
            $table->string('company_name');
            $table->string('contact_person');
            $table->date('tgl_rencana');
            $table->integer('jumlah_cerobong')->default(1);
            $table->json('parameters')->nullable();
            $table->text('lain_lain')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();

            $table->index('client_account_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sampling_requests');
    }
};
