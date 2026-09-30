document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formFilter');
    const dataUrl = form.dataset.routeData;
    const excelUrl = form.dataset.routeExportExcel;
    const pdfUrl = form.dataset.routeExportPdf;

    function renderList(elId, obj) {
        const el = document.getElementById(elId);
        const entries = Object.entries(obj || {});
        if (!entries.length) {
            el.innerHTML = '<li class="list-group-item text-secondary small">Tidak ada data</li>';
            return;
        }
        el.innerHTML = entries
            .map(([label, jumlah]) => `<li class="list-group-item d-flex justify-content-between small"><span>${label}</span><strong>${jumlah}</strong></li>`)
            .join('');
    }

    function muatData() {
        const params = new URLSearchParams(new FormData(form)).toString();
        fetch(dataUrl + '?' + params, { headers: { Accept: 'application/json' } })
            .then((res) => res.json())
            .then((json) => {
                document.getElementById('statTotal').textContent = json.total;
                document.getElementById('statSelesai').textContent = json.selesai;
                renderList('listKategori', json.per_kategori);
                renderList('listKomisi', json.per_komisi);
                renderList('listKecamatan', json.per_kecamatan);
                renderList('listStatus', json.per_status);
                document.getElementById('linkExportExcel').href = excelUrl + '?' + params;
                document.getElementById('linkExportPdf').href = pdfUrl + '?' + params;
            })
            .catch(() => AdminCrud.showToast('Gagal memuat data rekap.', 'danger'));
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        muatData();
    });

    muatData();
});
