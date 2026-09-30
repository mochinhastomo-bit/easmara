<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (config('recaptcha.site_key'))
        <meta name="recaptcha-site-key" content="{{ config('recaptcha.site_key') }}">
    @endif
    <title>@yield('title', 'e-ASMARA') &mdash; Sekretariat DPRD Kabupaten Gresik</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('beranda') }}">e-ASMARA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('beranda') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profil') }}">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('prosedur') }}">Prosedur</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('aspirasi.buat') }}">Kirim Aspirasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('aspirasi.status') }}">Cek Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('kontak') }}">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        <div class="container" style="max-width: 720px;">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-top py-3 text-center text-secondary small">
        @if (isset($mediaSosialFooter) && $mediaSosialFooter->isNotEmpty())
            <div class="mb-2">
                @foreach ($mediaSosialFooter as $media)
                    <a href="{{ $media->url }}" target="_blank" rel="noopener" class="text-secondary text-decoration-none me-3">{{ $media->platform }}</a>
                @endforeach
            </div>
        @endif
        &copy; {{ date('Y') }} Sekretariat DPRD Kabupaten Gresik &mdash; e-ASMARA
    </footer>
</body>
</html>
