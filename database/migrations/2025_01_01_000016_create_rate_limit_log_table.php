<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_limit_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('target')->index();
            $table->enum('jenis', ['otp', 'submit_aspirasi']);
            $table->unsignedInteger('jumlah_percobaan')->default(0);
            $table->timestamp('window_mulai')->nullable();
            $table->timestamp('window_selesai')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_limit_log');
    }
};
