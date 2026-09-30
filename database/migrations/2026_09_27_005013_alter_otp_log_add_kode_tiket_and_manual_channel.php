<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mendukung alur verifikasi WA manual: pelapor mengirim WA berisi kode_tiket
     * ke wa_dinas_nomor, staf membalas kode OTP secara manual (belum ada API
     * WhatsApp Business resmi dari Diskominfo). kode_otp_hash diganti jadi
     * terenkripsi (bukan hash satu arah) supaya staf bisa membaca kode aslinya
     * untuk dibalas manual.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE otp_log MODIFY channel ENUM('whatsapp', 'whatsapp_manual', 'email') NOT NULL DEFAULT 'whatsapp'");
        DB::statement('ALTER TABLE otp_log CHANGE kode_otp_hash kode_otp_enc VARCHAR(255) NOT NULL');

        Schema::table('otp_log', function ($table) {
            $table->string('kode_tiket')->nullable()->after('tujuan')->index();
        });
    }

    public function down(): void
    {
        Schema::table('otp_log', function ($table) {
            $table->dropColumn('kode_tiket');
        });

        DB::statement('ALTER TABLE otp_log CHANGE kode_otp_enc kode_otp_hash VARCHAR(255) NOT NULL');
        DB::statement("ALTER TABLE otp_log MODIFY channel ENUM('whatsapp', 'email') NOT NULL DEFAULT 'whatsapp'");
    }
};
