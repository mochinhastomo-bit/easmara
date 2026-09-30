<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\Aspirasi;
use App\Models\FotoBukti;
use App\Models\PengaturanSistem;
use App\Models\StatusLog;
use App\Services\OtpService;
use App\Services\PhoneNormalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OtpController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function showKirim()
    {
        $draft = session('aspirasi_draft');
        abort_unless($draft, 404, 'Sesi pengisian formulir tidak ditemukan. Silakan isi ulang formulir aspirasi.');

        return view('publik.aspirasi.verifikasi-hp', [
            'noHpMasked' => PhoneNormalizer::mask($draft['no_hp_normalized']),
        ]);
    }

    public function kirim(Request $request)
    {
        $draft = session('aspirasi_draft');
        abort_unless($draft, 404);

        $result = $this->otpService->requestOtp(
            $draft['no_hp_normalized'],
            $draft['email'] ?? null,
            $request->ip(),
            $draft['kode_tiket']
        );

        if (! $result['ok']) {
            return back()->withErrors(['otp' => $result['message']]);
        }

        session([
            'otp_channel' => $result['channel'],
            'otp_tujuan' => $result['channel'] === 'email' ? $draft['email'] : $draft['no_hp_normalized'],
        ]);

        return redirect()->route('aspirasi.verifikasi-otp');
    }

    public function showVerifikasi()
    {
        $tujuan = session('otp_tujuan');
        $draft = session('aspirasi_draft');
        abort_unless($tujuan && $draft, 404, 'Sesi verifikasi tidak ditemukan. Silakan mulai ulang dari formulir aspirasi.');

        $nomorDinas = PengaturanSistem::ambil('wa_dinas_nomor');
        $waLink = null;

        if (filled($nomorDinas)) {
            $nomorDinasWame = preg_replace('/\D/', '', $nomorDinas);
            $pesanWa = "Permohonan Verifikasi Nomor WhatsApp e-ASMARA. Kode Tiket: {$draft['kode_tiket']}. Mohon balas pesan ini dengan kode OTP saya.";
            $waLink = 'https://wa.me/'.$nomorDinasWame.'?text='.rawurlencode($pesanWa);
        }

        return view('publik.aspirasi.verifikasi-otp', [
            'channel' => session('otp_channel'),
            'kodeTiket' => $draft['kode_tiket'],
            'waLink' => $waLink,
            'noHp' => $draft['no_hp'],
        ]);
    }

    public function verifikasi(VerifyOtpRequest $request)
    {
        $tujuan = session('otp_tujuan');
        $channel = session('otp_channel');
        $draft = session('aspirasi_draft');
        abort_unless($tujuan && $draft, 404);

        // Cek nomor WA dulu (khusus channel berbasis WA) sebelum kode OTP --
        // supaya kesalahan ketik nomor tidak ikut menghabiskan jatah percobaan kode.
        if (in_array($channel, ['whatsapp', 'whatsapp_manual'], true)) {
            if (PhoneNormalizer::normalize($request->no_hp_konfirmasi) !== $draft['no_hp_normalized']) {
                return back()->withErrors(['no_hp_konfirmasi' => 'Nomor WhatsApp tidak sesuai dengan nomor yang didaftarkan pada aspirasi ini.']);
            }
        }

        $status = $this->otpService->verify($tujuan, $request->kode_otp);

        if ($status !== 'ok') {
            $pesan = match ($status) {
                'kadaluarsa' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode OTP.',
                'terlalu_banyak_percobaan' => 'Terlalu banyak percobaan kode yang salah. Silakan kirim ulang kode OTP.',
                'tidak_ditemukan' => 'Sesi kode OTP tidak ditemukan. Silakan kirim ulang kode OTP.',
                default => 'Kode OTP salah. Silakan periksa kembali kode yang Anda masukkan.',
            };

            return back()->withErrors(['kode_otp' => $pesan]);
        }

        $aspirasi = Aspirasi::create([
            'kode_tiket' => $draft['kode_tiket'],
            'nama_pelapor' => $draft['nama_pelapor'],
            'no_hp_enc' => $draft['no_hp_normalized'],
            'email' => $draft['email'] ?? null,
            'wilayah_id' => $draft['wilayah_id'],
            'kategori_id' => $draft['kategori_id'],
            'judul' => $draft['judul'],
            'uraian' => $draft['uraian'],
            'alamat_detail' => $draft['alamat_detail'] ?? null,
            'sumber' => 'online',
            'status_verifikasi' => 'menunggu',
            'is_spam' => $draft['is_spam'],
            'skor_spam' => $draft['skor_spam'],
            'ip_pengirim' => $draft['ip_pengirim'] ?? $request->ip(),
        ]);

        foreach ($draft['lampiran_temp'] ?? [] as $tempPath) {
            $permPath = 'foto-bukti/'.$aspirasi->id.'/'.basename($tempPath);
            Storage::disk('local')->move($tempPath, $permPath);

            FotoBukti::create([
                'aspirasi_id' => $aspirasi->id,
                'path_file' => $permPath,
                'tipe' => 'lampiran_publik',
            ]);
        }

        StatusLog::create([
            'aspirasi_id' => $aspirasi->id,
            'status_ke' => 'menunggu',
            'catatan' => 'Aspirasi diterima dari portal publik.',
        ]);

        session()->forget(['aspirasi_draft', 'otp_channel', 'otp_tujuan']);
        // Disengaja tidak pakai flash(): supaya halaman berhasil tetap tampil kalau di-refresh.
        session(['kode_tiket_terakhir' => $aspirasi->kode_tiket]);

        return redirect()->route('aspirasi.berhasil');
    }
}
