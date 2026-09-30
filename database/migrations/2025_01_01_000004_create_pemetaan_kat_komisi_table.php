<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemetaan_kat_komisi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->foreignUuid('komisi_id')->constrained('komisi')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kategori_id', 'komisi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemetaan_kat_komisi');
    }
};
