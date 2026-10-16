document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelPimpinan');
    const modalEl = document.getElementById('modalPimpinan');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formPimpinan');
    const addBtn = document.getElementById('btnTambahPimpinan');

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            {
                data: 'foto_url', name: 'foto', orderable: false, searchable: false,
                render: (url) => url ? `<img src="${url}" style="width:40px;height:40px;object-fit:cover;border-radius:50%">` : '—',
            },
            { data: 'nama', name: 'nama' },
            { data: 'jabatan', name: 'jabatan' },
            { data: 'urutan', name: 'urutan' },
            { data: 'status_badge', name: 'aktif', orderable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        modalEl.querySelector('.modal-title').textContent = 'Tambah Pimpinan DPRD';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-pimpinan');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="nama"]').value = btn.dataset.nama;
        form.querySelector('[name="jabatan"]').value = btn.dataset.jabatan;
        form.querySelector('[name="urutan"]').value = btn.dataset.urutan;
        form.querySelector('[name="aktif"]').checked = btn.dataset.aktif === '1';
        modalEl.querySelector('.modal-title').textContent = 'Ubah Pimpinan DPRD';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-pimpinan');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus "' + btn.dataset.nama + '"?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });
});
