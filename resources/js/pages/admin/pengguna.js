document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelPengguna');
    const modalEl = document.getElementById('modalPengguna');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formPengguna');
    const addBtn = document.getElementById('btnTambahPengguna');
    const selectRole = document.getElementById('selectRole');
    const fieldKomisi = document.getElementById('fieldKomisi');
    const fieldJabatan = document.getElementById('fieldJabatan');
    const fieldPassword = document.getElementById('fieldPassword');

    function toggleRoleFields() {
        const isKomisi = selectRole.value === 'komisi';
        fieldKomisi.classList.toggle('d-none', !isKomisi);
        fieldJabatan.classList.toggle('d-none', !isKomisi);
    }
    selectRole.addEventListener('change', toggleRoleFields);

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'komisi_nama', name: 'komisi.nama' },
            { data: 'jabatan_nama', name: 'jabatanKomisi.nama' },
            { data: 'status_badge', name: 'aktif', orderable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        form.querySelector('[name="password"]').required = true;
        fieldPassword.classList.remove('d-none');
        toggleRoleFields();
        modalEl.querySelector('.modal-title').textContent = 'Tambah Pengguna';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-pengguna');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="name"]').value = btn.dataset.name;
        form.querySelector('[name="email"]').value = btn.dataset.email;
        form.querySelector('[name="username"]').value = btn.dataset.username === 'null' ? '' : btn.dataset.username;
        form.querySelector('[name="role"]').value = btn.dataset.role;
        form.querySelector('[name="komisi_id"]').value = btn.dataset.komisi_id === 'null' ? '' : btn.dataset.komisi_id;
        form.querySelector('[name="jabatan_komisi_id"]').value = btn.dataset.jabatan_komisi_id === 'null' ? '' : btn.dataset.jabatan_komisi_id;
        form.querySelector('[name="aktif"]').checked = btn.dataset.aktif === '1';
        form.querySelector('[name="password"]').required = false;
        fieldPassword.classList.add('d-none');
        toggleRoleFields();
        modalEl.querySelector('.modal-title').textContent = 'Ubah Pengguna';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-pengguna');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus pengguna "' + btn.dataset.name + '"?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-reset-pengguna');
        if (!btn) return;
        AdminCrud.ajaxAction(btn.dataset.url, {
            confirmMessage: 'Reset sandi untuk "' + btn.dataset.name + '"? Sandi lama tidak akan berlaku lagi.',
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });
});
