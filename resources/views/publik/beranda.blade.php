@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    @if ($bannerList->isNotEmpty())
        <div id="carouselBanner" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner rounded-3 overflow-hidden">
                @foreach ($bannerList as $banner)
                    <div class="carousel-item @if ($loop->first) active @endif">
                        @if ($banner->url_tujuan)
                            <a href="{{ $banner->url_tujuan }}">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($banner->gambar) }}"
                                     class="d-block w-100" alt="{{ $banner->judul }}">
                            </a>
                        @else
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($banner->gambar) }}"
                                 class="d-block w-100" alt="{{ $banner->judul }}">
                        @endif
                        @if ($banner->judul)
                            <div class="carousel-caption d-none d-md-block">
                                <p class="bg-dark bg-opacity-50 rounded px-2 py-1 d-inline-block">{{ $banner->judul }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @if ($bannerList->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @endif

    <div class="text-center py-5">
        <h1 class="h2 mb-3">Selamat Datang di e-ASMARA</h1>
        <p class="text-secondary mb-4">
            Electronic Aspirasi Masyarakat &mdash; sampaikan aspirasi Anda langsung ke Komisi DPRD
            Kabupaten Gresik secara online, cepat, dan transparan.
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('aspirasi.buat') }}" class="btn btn-primary btn-lg">Kirim Aspirasi</a>
            <a href="{{ route('aspirasi.status') }}" class="btn btn-outline-secondary btn-lg">Cek Status</a>
        </div>
    </div>
@endsection
