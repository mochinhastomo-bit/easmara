document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelProsedur');
    const modalEl = document.getElementById('modalProsedur');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formProsedur');
    const addBtn = document.getElementById('btnTambahProsedur');

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            { data: 'jenis_badge', name: 'jenis', orderable: false },
            { data: 'judul', name: 'judul' },
            { data: 'urutan', name: 'urutan' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        modalEl.querySelector('.modal-title').textContent = 'Tambah Prosedur';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-prosedur');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="jenis"]').value = btn.dataset.jenis;
        form.querySelector('[name="judul"]').value = btn.dataset.judul;
        form.querySelector('[name="konten"]').value = btn.dataset.konten;
        form.querySelector('[name="urutan"]').value = btn.dataset.urutan;
        modalEl.querySelector('.modal-title').textContent = 'Ubah Prosedur';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-prosedur');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus prosedur "' + btn.dataset.judul + '"?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });
});
