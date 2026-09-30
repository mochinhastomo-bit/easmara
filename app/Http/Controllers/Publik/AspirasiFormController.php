<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAspirasiDraftRequest;
use App\Models\Kategori;
use App\Models\PengaturanSistem;
use App\Models\Wilayah;
use App\Services\PhoneNormalizer;
use App\Services\SpamScorer;
use App\Services\TicketNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\ImageManager;

class AspirasiFormController extends Controller
{
    public function create()
    {
        return view('publik.aspirasi.buat', [
            'kategoriList' => Kategori::where('aktif', true)->orderBy('nama')->get(),
            'kecamatanList' => Wilayah::where('jenis', 'kecamatan')->orderBy('nama')->get(),
            'lampiranMaksUkuranMb' => (int) PengaturanSistem::ambil('lampiran_maks_ukuran_mb', 5),
        ]);
    }

    /**
     * Ambil daftar desa/kelurahan untuk satu kecamatan (dipanggil via fetch dari form).
     */
    public function desaByKecamatan(Wilayah $kecamatan)
    {
        abort_unless($kecamatan->jenis === 'kecamatan', 404);

        return response()->json(
            $kecamatan->children()->orderBy('nama')->get(['id', 'nama', 'jenis'])
        );
    }

    public function berhasil()
    {
        $kodeTiket = session('kode_tiket_terakhir');
        abort_unless($kodeTiket, 404);

        return view('publik.aspirasi.berhasil', ['kodeTiket' => $kodeTiket]);
    }

    public function store(StoreAspirasiDraftRequest $request, SpamScorer $spamScorer)
    {
        $data = $request->safe()->except(['lampiran', 'recaptcha_token']);

        $lampiranTemp = [];
        foreach ($request->file('lampiran', []) as $file) {
            $lampiranTemp[] = $this->simpanLampiranSementara($file);
        }

        $spam = $spamScorer->skor($data['judul'], $data['uraian']);

        session([
            'aspirasi_draft' => [
                ...$data,
                'kode_tiket' => TicketNumberGenerator::generate(),
                'no_hp_normalized' => PhoneNormalizer::normalize($data['no_hp']),
                'lampiran_temp' => $lampiranTemp,
                'skor_spam' => $spam['skor'],
                'is_spam' => $spam['is_spam'],
                'ip_pengirim' => $request->ip(),
            ],
        ]);

        return redirect()->route('aspirasi.verifikasi-hp');
    }

    private function simpanLampiranSementara(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $path = 'tmp-uploads/'.session()->getId().'/'.Str::uuid().'.'.$ext;

        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $manager = new ImageManager(new Driver);
            $image = $manager->decodePath($file->getRealPath());
            $encoder = $ext === 'png' ? new PngEncoder : new JpegEncoder(quality: 85);
            Storage::disk('local')->put($path, (string) $image->encode($encoder));
        } else {
            Storage::disk('local')->putFileAs(dirname($path), $file, basename($path));
        }

        return $path;
    }
}
