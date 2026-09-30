<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') &mdash; e-ASMARA Admin</title>
    @vite(['resources/scss/admin.scss', 'resources/js/admin.js'])
</head>
<body>
    <div class="page">
        <aside class="navbar navbar-vertical navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="{{ route('admin.dashboard') }}">e-ASMARA</a>
                </h1>
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <span class="nav-link-icon"><i class="ti ti-layout-dashboard text-azure"></i></span>
                                <span class="nav-link-title">Dasbor</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.aspirasi.*') ? 'active' : '' }}" href="{{ route('admin.aspirasi.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-message-report text-indigo"></i></span>
                                <span class="nav-link-title">Aspirasi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}" href="{{ route('admin.rekap.index') }}">
                                <span class="nav-link-icon"><i class="ti ti-report text-teal"></i></span>
                                <span class="nav-link-title">Rekap</span>
                            </a>
                        </li>

                        @if (in_array(auth()->user()->role, ['administrator', 'verifikator']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.verifikasi-wa.*') ? 'active' : '' }}" href="{{ route('admin.verifikasi-wa.index') }}">
                                    <span class="nav-link-icon"><i class="ti ti-brand-whatsapp text-lime"></i></span>
                                    <span class="nav-link-title">Verifikasi WA Manual</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->role === 'administrator')
                            @php
                                $masterRoutes = ['admin.kategori.*','admin.komisi.*','admin.kamus-spam.*','admin.blacklist.*','admin.pengguna.*','admin.pengaturan.*'];
                                $profilRoutes = ['admin.profil-instansi.*','admin.pimpinan-dprd.*','admin.media-sosial.*','admin.prosedur.*','admin.banner-konten.*','admin.pesan-kontak.*'];
                            @endphp
                            <li class="nav-item dropdown {{ request()->routeIs($masterRoutes) ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs($masterRoutes) ? 'active' : '' }}" href="#sidebar-master" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="{{ request()->routeIs($masterRoutes) ? 'true' : 'false' }}">
                                    <span class="nav-link-icon"><i class="ti ti-database text-purple"></i></span>
                                    <span class="nav-link-title">Master Data</span>
                                </a>
                                <div class="dropdown-menu {{ request()->routeIs($masterRoutes) ? 'show' : '' }}">
                                    <a class="dropdown-item" href="{{ route('admin.kategori.index') }}">Kategori</a>
                                    <a class="dropdown-item" href="{{ route('admin.komisi.index') }}">Komisi</a>
                                    <a class="dropdown-item" href="{{ route('admin.kamus-spam.index') }}">Kamus Spam</a>
                                    <a class="dropdown-item" href="{{ route('admin.blacklist.index') }}">Blacklist Nomor</a>
                                    <a class="dropdown-item" href="{{ route('admin.pengguna.index') }}">Pengguna</a>
                                    <a class="dropdown-item" href="{{ route('admin.pengaturan.index') }}">Pengaturan Sistem</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown {{ request()->routeIs($profilRoutes) ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs($profilRoutes) ? 'active' : '' }}" href="#sidebar-profil" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="{{ request()->routeIs($profilRoutes) ? 'true' : 'false' }}">
                                    <span class="nav-link-icon"><i class="ti ti-building text-pink"></i></span>
                                    <span class="nav-link-title">Profil Instansi</span>
                                </a>
                                <div class="dropdown-menu {{ request()->routeIs($profilRoutes) ? 'show' : '' }}">
                                    <a class="dropdown-item" href="{{ route('admin.profil-instansi.index') }}">Profil Instansi</a>
                                    <a class="dropdown-item" href="{{ route('admin.pimpinan-dprd.index') }}">Pimpinan DPRD</a>
                                    <a class="dropdown-item" href="{{ route('admin.media-sosial.index') }}">Media Sosial</a>
                                    <a class="dropdown-item" href="{{ route('admin.prosedur.index') }}">Prosedur Layanan</a>
                                    <a class="dropdown-item" href="{{ route('admin.banner-konten.index') }}">Banner Konten</a>
                                    <a class="dropdown-item" href="{{ route('admin.pesan-kontak.index') }}">Pesan Kontak</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}" href="{{ route('admin.audit-log.index') }}">
                                    <span class="nav-link-icon"><i class="ti ti-history text-orange"></i></span>
                                    <span class="nav-link-title">Audit Log</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            @hasSection('pretitle')
                                <div class="page-pretitle">@yield('pretitle')</div>
                            @endif
                            <h2 class="page-title">@yield('title', 'Dasbor')</h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="dropdown">
                                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                                    <span class="avatar avatar-sm rounded-circle bg-primary-lt">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    <div class="d-none d-sm-block ps-2">
                                        <div>{{ auth()->user()->name }}</div>
                                        <div class="mt-1 small text-secondary">{{ ucfirst(auth()->user()->role) }}</div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
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
            </div>

            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center">
                        <div class="col text-secondary small">
                            &copy; {{ date('Y') }} Sekretariat DPRD Kabupaten Gresik &mdash; e-ASMARA
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3 z-toast" id="toastContainer"></div>
</body>
</html>
