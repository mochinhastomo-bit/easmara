<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspirasi_komisi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('aspirasi_id')->constrained('aspirasi')->cascadeOnDelete();
            $table->foreignUuid('komisi_id')->constrained('komisi')->cascadeOnDelete();
            $table->enum('status_tindak_lanjut', ['diterima', 'ditunda', 'diproses', 'ditolak', 'selesai'])
                ->default('diterima');
            $table->timestamps();

            $table->unique(['aspirasi_id', 'komisi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasi_komisi');
    }
};
