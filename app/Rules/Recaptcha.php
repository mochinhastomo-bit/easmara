<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Validasi token Google reCAPTCHA v3 (D5 -- CAPTCHA formulir publik).
 *
 * Pemakaian:
 *   'recaptcha_token' => ['required', new Recaptcha('kirim_aspirasi')],
 */
class Recaptcha implements ValidationRule
{
    public function __construct(private readonly string $expectedAction) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('recaptcha.secret_key');

        if (blank($secret)) {
            Log::warning('reCAPTCHA secret key belum dikonfigurasi -- verifikasi dilewati.');

            return;
        }

        $response = Http::asForm()->post(config('recaptcha.verify_url'), [
            'secret' => $secret,
            'response' => $value,
        ]);

        $result = $response->json();

        if (! ($result['success'] ?? false)) {
            $fail('Verifikasi CAPTCHA gagal. Silakan coba lagi.');

            return;
        }

        if (($result['action'] ?? null) !== $this->expectedAction) {
            $fail('Verifikasi CAPTCHA tidak valid untuk aksi ini.');

            return;
        }

        if (($result['score'] ?? 0) < config('recaptcha.score_threshold')) {
            $fail('Permintaan terdeteksi mencurigakan. Silakan coba lagi.');
        }
    }
}
