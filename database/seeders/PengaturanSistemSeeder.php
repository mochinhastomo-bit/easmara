<?php

namespace Database\Seeders;

use App\Models\PengaturanSistem;
use Illuminate\Database\Seeder;

class PengaturanSistemSeeder extends Seeder
{
    /**
     * Nilai default persis sama dengan yang sebelumnya hardcode di kode --
     * mengisi baris ini TIDAK mengubah perilaku aplikasi sampai admin
     * sengaja mengedit nilainya lewat halaman Pengaturan Sistem.
     */
    public function run(): void
    {
        $daftar = [
            [
                'key' => 'otp_masa_berlaku_menit',
                'value' => '5',
                'keterangan' => 'Lama kode OTP berlaku sebelum kadaluarsa (menit).',
            ],
            [
                'key' => 'otp_maks_per_hari',
                'value' => '3',
                'keterangan' => 'Batas maksimal permintaan OTP per nomor HP per hari.',
            ],
            [
                'key' => 'lampiran_maks_ukuran_mb',
                'value' => '5',
                'keterangan' => 'Ukuran maksimal tiap file lampiran aspirasi (MB).',
            ],
            [
                'key' => 'spam_ambang_skor',
                'value' => '10',
                'keterangan' => 'Skor kamus spam minimal agar aspirasi otomatis ditandai spam (M3).',
            ],
            [
                'key' => 'wa_dinas_nomor',
                'value' => '',
                'keterangan' => 'Nomor WA resmi Dinas Kominfo Gresik -- otomatis disertakan sebagai info pengirim di setiap pesan OTP. Kosongkan info ini tidak ditampilkan. Update di sini kalau Pemkab ganti nomor, tidak perlu ubah kode.',
            ],
        ];

        foreach ($daftar as $item) {
            PengaturanSistem::firstOrCreate(['key' => $item['key']], [
                'value' => $item['value'],
                'keterangan' => $item['keterangan'],
            ]);
        }
    }
}
