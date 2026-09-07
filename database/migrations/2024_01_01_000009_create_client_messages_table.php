<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_messages', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('sender_name');
            $table->string('subject');
            $table->text('message');
            $table->text('reply')->nullable();
            $table->string('replied_by')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->string('status')->default('Belum Dibalas');
            $table->timestamps();

            $table->index('company_name');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_messages');
    }
};
