{{--
    Partial CRUD generik untuk master data berpola sama: nama, deskripsi, aktif.
    Parameter wajib: $routePrefix (mis. 'admin.kategori'), $label (mis. 'Kategori'), $slug (mis. 'kategori')
--}}
<div data-crud-slug="{{ $slug }}" data-crud-label="{{ $label }}"
     data-crud-route-data="{{ route($routePrefix . '.data') }}"
     data-crud-route-store="{{ route($routePrefix . '.store') }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $label }}</h1>
        <button type="button" class="btn btn-cta btn-sm" id="btnTambah{{ $slug }}">+ Tambah {{ $label }}</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabel{{ $slug }}" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal{{ $slug }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form{{ $slug }}" data-method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $label }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktif{{ $slug }}" checked>
                            <label class="form-check-label" for="aktif{{ $slug }}">Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@vite(['resources/js/pages/admin/simple-master-crud.js'])
