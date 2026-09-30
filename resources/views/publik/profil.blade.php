@extends('layouts.app')

@section('title', 'Profil Instansi')

@section('content')
    <h1 class="h4 mb-4">Profil Instansi</h1>

    @forelse ($bagianProfil as $bagian)
        <div class="mb-4">
            <h2 class="h5">{{ $bagian->judul }}</h2>
            <div style="white-space: pre-wrap;">{{ $bagian->konten }}</div>
        </div>
    @empty
        <p class="text-secondary">Profil instansi belum tersedia.</p>
    @endforelse

    @if ($pimpinan->isNotEmpty())
        <hr class="my-4">
        <h2 class="h5 mb-3">Pimpinan DPRD</h2>
        <div class="row g-3">
            @foreach ($pimpinan as $p)
                <div class="col-6 col-md-4 text-center">
                    @if ($p->foto)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($p->foto) }}"
                             class="rounded-circle mb-2" style="width:96px;height:96px;object-fit:cover;" alt="{{ $p->nama }}">
                    @else
                        <div class="rounded-circle bg-secondary-subtle mx-auto mb-2 d-flex align-items-center justify-content-center"
                             style="width:96px;height:96px;">
                            <span class="text-secondary small">Tanpa foto</span>
                        </div>
                    @endif
                    <div class="fw-semibold small">{{ $p->nama }}</div>
                    <div class="text-secondary small">{{ $p->jabatan }}</div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
