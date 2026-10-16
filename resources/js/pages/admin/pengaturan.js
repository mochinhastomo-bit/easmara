document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelPengaturan');
    const modalEl = document.getElementById('modalPengaturan');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formPengaturan');
    const addBtn = document.getElementById('btnTambahPengaturan');

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            { data: 'key', name: 'key' },
            { data: 'value', name: 'value', defaultContent: '—' },
            { data: 'keterangan', name: 'keterangan', defaultContent: '—' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        modalEl.querySelector('.modal-title').textContent = 'Tambah Pengaturan';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-pengaturan');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="key"]').value = btn.dataset.key;
        form.querySelector('[name="value"]').value = btn.dataset.value;
        form.querySelector('[name="keterangan"]').value = btn.dataset.keterangan;
        modalEl.querySelector('.modal-title').textContent = 'Ubah Pengaturan';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-pengaturan');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus pengaturan "' + btn.dataset.key + '"?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });
});
