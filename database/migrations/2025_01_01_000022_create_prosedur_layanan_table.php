<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prosedur_layanan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis', ['online', 'offline']);
            $table->string('judul');
            $table->longText('konten')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prosedur_layanan');
    }
};
