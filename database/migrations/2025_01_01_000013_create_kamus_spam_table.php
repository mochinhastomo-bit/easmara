<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamus_spam', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kata', 100)->unique();
            $table->integer('bobot_skor')->default(1);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamus_spam');
    }
};
