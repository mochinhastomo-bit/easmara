document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelProfil');
    const modalEl = document.getElementById('modalProfil');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formProfil');
    const addBtn = document.getElementById('btnTambahProfil');

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            { data: 'judul', name: 'judul' },
            { data: 'konten_ringkas', name: 'konten', orderable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        modalEl.querySelector('.modal-title').textContent = 'Tambah Bagian Profil';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-profil');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="judul"]').value = btn.dataset.judul;
        form.querySelector('[name="konten"]').value = btn.dataset.konten;
        modalEl.querySelector('.modal-title').textContent = 'Ubah Bagian Profil';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-profil');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus bagian "' + btn.dataset.judul + '"?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });
});
