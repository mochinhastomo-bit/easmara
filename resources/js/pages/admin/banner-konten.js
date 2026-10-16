document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelBanner');
    const modalEl = document.getElementById('modalBanner');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formBanner');
    const addBtn = document.getElementById('btnTambahBanner');

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            {
                data: 'gambar_url', name: 'gambar', orderable: false, searchable: false,
                render: (url) => `<img src="${url}" style="height:40px;object-fit:cover;">`,
            },
            { data: 'judul', name: 'judul', defaultContent: '—' },
            { data: 'url_tujuan', name: 'url_tujuan', defaultContent: '—' },
            { data: 'urutan', name: 'urutan' },
            { data: 'status_badge', name: 'aktif', orderable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        form.querySelector('[name="gambar"]').required = true;
        document.getElementById('labelGambarWajib').textContent = '(wajib, maks. 2MB)';
        modalEl.querySelector('.modal-title').textContent = 'Tambah Banner';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-banner');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="judul"]').value = btn.dataset.judul;
        form.querySelector('[name="url_tujuan"]').value = btn.dataset.url_tujuan;
        form.querySelector('[name="urutan"]').value = btn.dataset.urutan;
        form.querySelector('[name="aktif"]').checked = btn.dataset.aktif === '1';
        form.querySelector('[name="gambar"]').required = false;
        document.getElementById('labelGambarWajib').textContent = '(kosongkan untuk mempertahankan gambar lama)';
        modalEl.querySelector('.modal-title').textContent = 'Ubah Banner';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-banner');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus banner "' + (btn.dataset.judul || '(tanpa judul)') + '"?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });
});
