document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelMediaSosial');
    const modalEl = document.getElementById('modalMediaSosial');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formMediaSosial');
    const addBtn = document.getElementById('btnTambahMediaSosial');

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            { data: 'platform', name: 'platform' },
            { data: 'url', name: 'url' },
            { data: 'ikon', name: 'ikon', defaultContent: '—' },
            { data: 'urutan', name: 'urutan' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        modalEl.querySelector('.modal-title').textContent = 'Tambah Media Sosial';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-mediasosial');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="platform"]').value = btn.dataset.platform;
        form.querySelector('[name="url"]').value = btn.dataset.link;
        form.querySelector('[name="ikon"]').value = btn.dataset.ikon;
        form.querySelector('[name="urutan"]').value = btn.dataset.urutan;
        modalEl.querySelector('.modal-title').textContent = 'Ubah Media Sosial';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-mediasosial');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus media sosial "' + btn.dataset.platform + '"?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });
});
