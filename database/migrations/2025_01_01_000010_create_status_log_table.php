<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('aspirasi_id')->constrained('aspirasi')->cascadeOnDelete();
            $table->foreignUuid('aspirasi_komisi_id')->nullable()->constrained('aspirasi_komisi')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_dari', 50)->nullable();
            $table->string('status_ke', 50);
            $table->longText('catatan')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_log');
    }
};
