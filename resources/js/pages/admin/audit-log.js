document.addEventListener('DOMContentLoaded', function () {
    const tableEl = document.getElementById('tabelAuditLog');

    $(tableEl).DataTable({
        processing: true, serverSide: true,
        ajax: tableEl.dataset.routeData,
        order: [[0, 'desc']],
        columns: [
            { data: 'created_at_fmt', name: 'created_at_fmt' },
            { data: 'user_nama', name: 'user.name' },
            { data: 'aksi', name: 'aksi' },
            { data: 'model_terkait', name: 'model_terkait', defaultContent: '—' },
            { data: 'ip_address', name: 'ip_address', defaultContent: '—' },
        ],
    });
});
