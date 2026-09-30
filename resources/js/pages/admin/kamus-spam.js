document.addEventListener('DOMContentLoaded', function () {
    const tableKamusEl = document.getElementById('tabelKamusSpam');
    const tableUsulanEl = document.getElementById('tabelUsulanSpam');
    const modalEl = document.getElementById('modalKamus');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formKamus');
    const addBtn = document.getElementById('btnTambahKamus');

    const tabelKamus = $(tableKamusEl).DataTable({
        processing: true, serverSide: true,
        ajax: tableKamusEl.dataset.routeData,
        columns: [
            { data: 'kata', name: 'kata' },
            { data: 'bobot_skor', name: 'bobot_skor' },
            { data: 'status_badge', name: 'aktif', orderable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    const tabelUsulan = $(tableUsulanEl).DataTable({
        processing: true, serverSide: true,
        ajax: tableUsulanEl.dataset.routeData,
        columns: [
            { data: 'kata', name: 'kata' },
            { data: 'diusulkan_oleh_nama', name: 'diusulkanOleh.name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = addBtn.dataset.routeStore;
        form.querySelector('[name="_method"]').value = '';
        modalEl.querySelector('.modal-title').textContent = 'Tambah Kata';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-kamusspam');
        if (!btn) return;
        form.reset();
        form.action = btn.dataset.url;
        form.querySelector('[name="_method"]').value = 'PUT';
        form.querySelector('[name="kata"]').value = btn.dataset.kata;
        form.querySelector('[name="bobot_skor"]').value = btn.dataset.bobot_skor;
        form.querySelector('[name="aktif"]').checked = btn.dataset.aktif === '1';
        modalEl.querySelector('.modal-title').textContent = 'Ubah Kata';
        modal.show();
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-kamusspam');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus kata "' + btn.dataset.kata + '" dari kamus spam?',
            onSuccess: () => tabelKamus.ajax.reload(null, false),
        });
    });

    document.addEventListener('click', function (e) {
        const setuju = e.target.closest('.btn-setujui-usulan');
        const tolak = e.target.closest('.btn-tolak-usulan');
        if (setuju) {
            AdminCrud.ajaxAction(setuju.dataset.url, {
                onSuccess: () => { tabelUsulan.ajax.reload(null, false); tabelKamus.ajax.reload(null, false); },
            });
        } else if (tolak) {
            AdminCrud.ajaxAction(tolak.dataset.url, {
                confirmMessage: 'Tolak usulan kata ini?',
                onSuccess: () => tabelUsulan.ajax.reload(null, false),
            });
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => tabelKamus.ajax.reload(null, false) });
    });
});
