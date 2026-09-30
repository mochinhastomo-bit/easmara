<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * CSP ketat + header keamanan lain (OWASP Top 10 / M7).
 *
 * Semua skrip & style wajib berasal dari file ('self') atau memakai nonce
 * per-request -- tidak ada 'unsafe-inline' untuk script maupun <style>.
 * Kebijakan SAMA PERSIS di semua environment (local/staging/produksi) --
 * seluruh halaman (admin + publik) sudah 100% pakai JS eksternal lewat Vite
 * (lihat resources/js/pages/), tidak ada lagi <script>/<style> inline di
 * view manapun, jadi tidak perlu pengecualian local lagi.
 *
 * style-src-attr sengaja dipisah dan tetap 'unsafe-inline': Bootstrap Modal,
 * DataTables Responsive, dan Chart.js menyetel atribut style="..." lewat
 * DOM API saat runtime (bukan <style> block) -- ini scope-nya sempit (cuma
 * atribut style, tidak bisa dipakai eksekusi JS) jadi risikonya rendah.
 * Domain Google diizinkan khusus untuk reCAPTCHA v3.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = Str::random(24);
        $request->attributes->set('csp_nonce', $nonce);
        View::share('cspNonce', $nonce);

        /** @var Response $response */
        $response = $next($request);

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' https://www.google.com https://www.gstatic.com",
            "style-src 'self' 'nonce-{$nonce}'",
            "style-src-attr 'unsafe-inline'",
            "img-src 'self' data: https://www.gstatic.com",
            "font-src 'self' data:",
            "connect-src 'self' https://www.google.com",
            "frame-src 'self' https://www.google.com",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "object-src 'none'",
            'upgrade-insecure-requests',
        ]);

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=(), payment=()');
        $response->headers->set('X-XSS-Protection', '0');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
