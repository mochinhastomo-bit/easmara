@extends('layouts.app')

@section('title', 'Prosedur Layanan')

@section('content')
    <h1 class="h4 mb-4">Prosedur Layanan</h1>

    <h2 class="h5">Prosedur Online</h2>
    @forelse ($prosedurOnline as $p)
        <div class="card mb-2">
            <div class="card-body">
                <h3 class="h6 mb-2">{{ $p->judul }}</h3>
                <div style="white-space: pre-wrap;">{{ $p->konten }}</div>
            </div>
        </div>
    @empty
        <p class="text-secondary small">Belum ada prosedur online.</p>
    @endforelse

    <h2 class="h5 mt-4">Prosedur Offline</h2>
    @forelse ($prosedurOffline as $p)
        <div class="card mb-2">
            <div class="card-body">
                <h3 class="h6 mb-2">{{ $p->judul }}</h3>
                <div style="white-space: pre-wrap;">{{ $p->konten }}</div>
            </div>
        </div>
    @empty
        <p class="text-secondary small">Belum ada prosedur offline.</p>
    @endforelse
@endsection
