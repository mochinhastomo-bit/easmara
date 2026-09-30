<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $perluKonfirmasiNomor = in_array(session('otp_channel'), ['whatsapp', 'whatsapp_manual'], true);

        return [
            'kode_otp' => ['required', 'digits:6'],
            'no_hp_konfirmasi' => [$perluKonfirmasiNomor ? 'required' : 'nullable', 'string'],
        ];
    }
}
