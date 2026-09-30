document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelPesan');
    const modalEl = document.getElementById('modalPesan');
    const modal = new bootstrap.Modal(modalEl);
    let currentUpdateUrl = null;

    const table = $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: tableEl.dataset.routeData,
        order: [[3, 'desc']],
        columns: [
            { data: 'nama', name: 'nama' },
            { data: 'subjek', name: 'subjek', defaultContent: '—' },
            { data: 'status_badge', name: 'status', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-lihat-pesan');
        if (!btn) return;

        fetch(btn.dataset.url, { headers: { Accept: 'application/json' } })
            .then((res) => res.json())
            .then((data) => {
                document.getElementById('pesanNama').textContent = data.nama;
                document.getElementById('pesanEmail').textContent = data.email || '—';
                document.getElementById('pesanSubjek').textContent = data.subjek || '—';
                document.getElementById('pesanTanggal').textContent = data.created_at;
                document.getElementById('pesanIsi').textContent = data.pesan;
                document.getElementById('pesanStatusSelect').value = data.status;
                currentUpdateUrl = btn.dataset.updateUrl;
                modal.show();
                table.ajax.reload(null, false);
            });
    });

    document.getElementById('btnSimpanStatusPesan').addEventListener('click', function () {
        if (!currentUpdateUrl) return;
        AdminCrud.ajaxAction(currentUpdateUrl, {
            method: 'PUT',
            data: { status: document.getElementById('pesanStatusSelect').value },
            onSuccess: () => {
                modal.hide();
                table.ajax.reload(null, false);
            },
        });
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-pesan');
        if (!btn) return;
        AdminCrud.ajaxDelete(btn.dataset.url, {
            confirmMessage: 'Hapus pesan ini?',
            onSuccess: () => table.ajax.reload(null, false),
        });
    });
});
