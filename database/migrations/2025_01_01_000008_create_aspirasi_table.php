<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_tiket', 50)->unique();
            $table->string('kode_lama', 50)->nullable();
            $table->string('nama_pelapor');
            $table->string('nik', 20)->nullable();
            $table->text('no_hp_enc');
            $table->string('email')->nullable();
            $table->foreignUuid('wilayah_id')->nullable()->constrained('wilayah')->nullOnDelete();
            $table->foreignUuid('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->string('judul');
            $table->longText('uraian');
            $table->longText('alamat_detail')->nullable();
            $table->enum('sumber', ['online', 'offline'])->default('online');
            $table->enum('status_verifikasi', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');
            $table->boolean('is_spam')->default(false);
            $table->integer('skor_spam')->default(0);
            $table->enum('status_pimpinan', ['menunggu', 'ditindaklanjuti', 'ditolak'])->nullable();
            $table->longText('catatan_pimpinan')->nullable();
            $table->foreignUuid('disalurkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disalurkan_at')->nullable();
            $table->string('ip_pengirim', 45)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasi');
    }
};
