<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_bukti', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('aspirasi_id')->constrained('aspirasi')->cascadeOnDelete();
            $table->foreignUuid('status_log_id')->nullable()->constrained('status_log')->cascadeOnDelete();
            $table->string('path_file');
            $table->enum('tipe', ['lampiran_publik', 'dokumentasi_proses'])->default('lampiran_publik');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_bukti');
    }
};
