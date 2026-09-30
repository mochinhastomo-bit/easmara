document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-crud-slug]').forEach(function (wrapper) {
        const slug = wrapper.dataset.crudSlug;
        const label = wrapper.dataset.crudLabel;
        const routeData = wrapper.dataset.crudRouteData;
        const routeStore = wrapper.dataset.crudRouteStore;

        const modalEl = document.getElementById('modal' + slug);
        const modal = new bootstrap.Modal(modalEl);
        const form = document.getElementById('form' + slug);

        const table = $('#tabel' + slug).DataTable({
            processing: true,
            serverSide: true,
            ajax: routeData,
            columns: [
                { data: 'nama', name: 'nama' },
                { data: 'deskripsi', name: 'deskripsi', defaultContent: '—' },
                { data: 'status_badge', name: 'aktif', orderable: false },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ],
        });

        const addBtn = document.getElementById('btnTambah' + slug);
        addBtn?.addEventListener('click', function () {
            form.reset();
            form.action = routeStore;
            form.querySelector('[name="_method"]').value = '';
            modalEl.querySelector('.modal-title').textContent = 'Tambah ' + label;
            modal.show();
        });

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-edit-' + slug);
            if (!btn) return;
            form.reset();
            form.action = btn.dataset.url;
            form.querySelector('[name="_method"]').value = 'PUT';
            form.querySelector('[name="nama"]').value = btn.dataset.nama;
            form.querySelector('[name="deskripsi"]').value = btn.dataset.deskripsi || '';
            form.querySelector('[name="aktif"]').checked = btn.dataset.aktif === '1';
            modalEl.querySelector('.modal-title').textContent = 'Ubah ' + label;
            modal.show();
        });

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-hapus-' + slug);
            if (!btn) return;
            AdminCrud.ajaxDelete(btn.dataset.url, {
                confirmMessage: 'Hapus ' + label + ' "' + btn.dataset.nama + '"?',
                onSuccess: () => table.ajax.reload(null, false),
            });
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
        });
    });
});
