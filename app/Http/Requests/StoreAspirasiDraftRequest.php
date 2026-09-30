<?php

namespace App\Http\Requests;

use App\Models\PengaturanSistem;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class StoreAspirasiDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maksUkuranMb = (int) PengaturanSistem::ambil('lampiran_maks_ukuran_mb', 5);

        return [
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'min:9', 'max:15'],
            'email' => ['nullable', 'email', 'max:255'],
            'wilayah_id' => ['required', 'uuid', 'exists:wilayah,id'],
            'kategori_id' => ['required', 'uuid', 'exists:kategori,id'],
            'judul' => ['required', 'string', 'max:255'],
            'uraian' => ['required', 'string', 'min:20'],
            'alamat_detail' => ['nullable', 'string'],
            'lampiran' => ['nullable', 'array', 'max:5'],
            'lampiran.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:'.($maksUkuranMb * 1024)],
            'consent_pdpb' => ['accepted'],
            'recaptcha_token' => [
                config('recaptcha.site_key') ? 'required' : 'nullable',
                new Recaptcha('kirim_aspirasi'),
            ],
        ];
    }

    public function messages(): array
    {
        $maksUkuranMb = (int) PengaturanSistem::ambil('lampiran_maks_ukuran_mb', 5);

        return [
            'consent_pdpb.accepted' => 'Anda wajib menyetujui pernyataan perlindungan data pribadi.',
            'uraian.min' => 'Uraian aspirasi terlalu singkat, mohon dijelaskan lebih detail.',
            'lampiran.*.mimes' => 'Lampiran hanya boleh berformat JPG, PNG, atau PDF.',
            'lampiran.*.max' => "Ukuran tiap lampiran maksimal {$maksUkuranMb}MB.",
        ];
    }
}
