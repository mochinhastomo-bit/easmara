document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelBlacklist');
    const form = document.getElementById('formBlacklist');

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: AdminCrud.dtAjax(tableEl.dataset.routeData),
        columns: [
            { data: 'no_hp', name: 'no_hp_enc', orderable: false, searchable: false },
            { data: 'alasan', name: 'alasan', defaultContent: '—' },
            { data: 'diblokir_oleh_nama', name: 'diblokirOleh.name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        AdminCrud.ajaxSubmit(form, { onSuccess: () => table.ajax.reload(null, false) });
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-blacklist');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus nomor ini dari blacklist?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });
});
