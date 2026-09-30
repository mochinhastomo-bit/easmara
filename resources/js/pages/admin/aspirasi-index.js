document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelAspirasi');

    $(tableEl).DataTable({
        processing: true,
        serverSide: true,
        ajax: tableEl.dataset.routeData,
        columns: [
            { data: 'kode_tiket', name: 'kode_tiket' },
            { data: 'nama_pelapor', name: 'nama_pelapor' },
            { data: 'kategori_nama', name: 'kategori.nama' },
            { data: 'wilayah_nama', name: 'wilayah.nama' },
            { data: 'komisi_nama', name: 'komisi.nama', orderable: false, searchable: false },
            { data: 'status_badge', name: 'status_verifikasi', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
        order: [[6, 'desc']],
    });
});
