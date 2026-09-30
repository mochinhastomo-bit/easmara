<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tujuan')->index();
            $table->enum('channel', ['whatsapp', 'email'])->default('whatsapp');
            $table->string('kode_otp_hash');
            $table->enum('status', ['terkirim', 'gagal', 'terpakai', 'kadaluarsa'])->default('terkirim');
            $table->unsignedInteger('percobaan')->default(0);
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_log');
    }
};
