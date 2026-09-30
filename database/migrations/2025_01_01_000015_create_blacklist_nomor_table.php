<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklist_nomor', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('no_hp_enc');
            $table->longText('alasan')->nullable();
            $table->foreignUuid('diblokir_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklist_nomor');
    }
};
