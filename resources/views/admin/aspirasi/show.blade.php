@extends('layouts.admin')

@section('title', 'Detail Aspirasi')
@section('pretitle', 'Manajemen Aspirasi')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1 class="h4 mb-0">{{ $aspirasi->judul }}</h1>
        <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Kembali</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header">Data Aspirasi</div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4">Kode Tiket</dt>
                        <dd class="col-sm-8 font-monospace">{{ $aspirasi->kode_tiket }}</dd>

                        <dt class="col-sm-4">Nama Pelapor</dt>
                        <dd class="col-sm-8">{{ $aspirasi->nama_pelapor }}</dd>

                        <dt class="col-sm-4">Nomor HP</dt>
                        <dd class="col-sm-8">{{ $aspirasi->no_hp_enc }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $aspirasi->email ?? '—' }}</dd>

                        <dt class="col-sm-4">Kategori</dt>
                        <dd class="col-sm-8">
                            @if ($aspirasi->kategori)
                                <span class="badge bg-{{ \App\Support\LabelWarna::warna($aspirasi->kategori->nama) }}-lt">{{ $aspirasi->kategori->nama }}</span>
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-4">Wilayah</dt>
                        <dd class="col-sm-8">{{ $aspirasi->wilayah?->nama ?? 'Belum dipetakan' }}</dd>

                        <dt class="col-sm-4">Alamat Detail</dt>
                        <dd class="col-sm-8">{{ $aspirasi->alamat_detail ?? '—' }}</dd>

                        <dt class="col-sm-4">Uraian</dt>
                        <dd class="col-sm-8" style="white-space: pre-wrap;">{{ $aspirasi->uraian }}</dd>

                        <dt class="col-sm-4">Sumber</dt>
                        <dd class="col-sm-8">{{ ucfirst($aspirasi->sumber) }}</dd>

                        <dt class="col-sm-4">Skor Spam</dt>
                        <dd class="col-sm-8">
                            {{ $aspirasi->skor_spam }}
                            @if ($aspirasi->is_spam) <span class="badge text-bg-warning">Ditandai Spam</span> @endif
                        </dd>
                    </dl>
                </div>
            </div>

            @if ($aspirasi->fotoBukti->isNotEmpty())
                <div class="card mb-3">
                    <div class="card-header">Lampiran</div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        @foreach ($aspirasi->fotoBukti as $foto)
                            @php
                                $ext = strtolower(pathinfo($foto->path_file, PATHINFO_EXTENSION));
                                $tipeFile = in_array($ext, ['jpg', 'jpeg', 'png']) ? 'gambar' : 'pdf';
                            @endphp
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-lihat-lampiran"
                                    data-url="{{ $foto->signedUrl() }}" data-tipe="{{ $tipeFile }}">
                                Lampiran {{ $loop->iteration }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">Riwayat Status</div>
                <ul class="list-group list-group-flush">
                    @forelse ($aspirasi->statusLog as $log)
                        <li class="list-group-item small">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">{{ $log->status_ke }}</span>
                                <span class="text-secondary">{{ $log->created_at?->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            @if ($log->catatan)
                                <div class="text-secondary">{{ $log->catatan }}</div>
                            @endif
                            @if ($log->user)
                                <div class="text-secondary fst-italic">oleh {{ $log->user->name }}</div>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-secondary small">Belum ada riwayat.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-5">
            @can('verifikasi', App\Models\Aspirasi::class)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Verifikasi &amp; Kategorisasi</span>
                        <span class="badge text-bg-{{ \App\Support\StatusWarna::verifikasi($aspirasi->status_verifikasi) }}">{{ ucfirst($aspirasi->status_verifikasi) }}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.aspirasi.verifikasi', $aspirasi) }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Status Verifikasi</label>
                                <select name="status_verifikasi" class="form-select form-select-sm">
                                    <option value="diverifikasi" @selected($aspirasi->status_verifikasi === 'diverifikasi')>Diverifikasi</option>
                                    <option value="ditolak" @selected($aspirasi->status_verifikasi === 'ditolak')>Ditolak</option>
                                </select>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="is_spam" value="1" class="form-check-input" id="is_spam"
                                       @checked($aspirasi->is_spam)>
                                <label class="form-check-label small" for="is_spam">Tandai sebagai spam</label>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Catatan</label>
                                <textarea name="catatan" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Verifikasi</button>
                        </form>
                    </div>
                </div>
            @endcan

            @can('salurkan', App\Models\Aspirasi::class)
                <div class="card mb-3">
                    <div class="card-header">Salurkan ke Komisi</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.aspirasi.salurkan', $aspirasi) }}">
                            @csrf
                            @foreach ($komisiList as $komisi)
                                <div class="form-check">
                                    <input type="checkbox" name="komisi_id[]" value="{{ $komisi->id }}"
                                           class="form-check-input" id="komisi_{{ $komisi->id }}"
                                           @checked($aspirasi->komisi->contains('id', $komisi->id))>
                                    <label class="form-check-label small" for="komisi_{{ $komisi->id }}">
                                        <span class="badge bg-{{ \App\Support\LabelWarna::warna($komisi->nama) }}-lt">{{ $komisi->nama }}</span>
                                    </label>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Salurkan</button>
                        </form>
                    </div>
                </div>
            @endcan

            @can('updateTindakLanjut', $aspirasi)
                @foreach ($aspirasi->aspirasiKomisi as $pivot)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                Tindak Lanjut &mdash;
                                <span class="badge bg-{{ \App\Support\LabelWarna::warna($pivot->komisi->nama) }}-lt">{{ $pivot->komisi->nama }}</span>
                            </span>
                            <span class="badge text-bg-{{ \App\Support\StatusWarna::tindakLanjut($pivot->status_tindak_lanjut) }}">{{ ucfirst($pivot->status_tindak_lanjut) }}</span>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.aspirasi.tindak-lanjut', $aspirasi) }}">
                                @csrf
                                <input type="hidden" name="aspirasi_komisi_id" value="{{ $pivot->id }}">
                                <div class="mb-2">
                                    <select name="status_tindak_lanjut" class="form-select form-select-sm">
                                        @foreach (['diterima', 'ditunda', 'diproses', 'ditolak', 'selesai'] as $s)
                                            <option value="{{ $s }}" @selected($pivot->status_tindak_lanjut === $s)>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <textarea name="catatan" class="form-control form-control-sm" rows="2"
                                              placeholder="Catatan tindak lanjut"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Perbarui</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endcan

            @can('tinjauPimpinan', App\Models\Aspirasi::class)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Tinjauan Pimpinan</span>
                        <span class="badge text-bg-{{ \App\Support\StatusWarna::pimpinan($aspirasi->status_pimpinan) }}">{{ ucfirst($aspirasi->status_pimpinan ?? 'menunggu') }}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.aspirasi.pimpinan', $aspirasi) }}">
                            @csrf
                            <div class="mb-2">
                                <select name="status_pimpinan" class="form-select form-select-sm">
                                    @foreach (['menunggu', 'ditindaklanjuti', 'ditolak'] as $s)
                                        <option value="{{ $s }}" @selected($aspirasi->status_pimpinan === $s)>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea name="catatan_pimpinan" class="form-control form-control-sm" rows="2">{{ $aspirasi->catatan_pimpinan }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>

    <div class="modal fade" id="modalLampiran" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="lampiranGambar" src="" class="img-fluid" alt="Lampiran" style="display:none;">
                    <iframe id="lampiranPdf" src="" style="display:none; width:100%; height:75vh; border:0;"></iframe>
                </div>
                <div class="modal-footer">
                    <a id="lampiranBukaBaru" href="#" target="_blank" class="btn btn-outline-secondary btn-sm">Buka di Tab Baru</a>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/pages/admin/aspirasi-show.js'])
@endsection
