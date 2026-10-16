document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formFilter');
    const dataUrl = form.dataset.routeData;

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
        AdminCrud.postJson(dataUrl, new FormData(form))
            .then((json) => {
                document.getElementById('statTotal').textContent = json.total;
                document.getElementById('statSelesai').textContent = json.selesai;
                renderList('listKategori', json.per_kategori);
                renderList('listKomisi', json.per_komisi);
                renderList('listKecamatan', json.per_kecamatan);
                renderList('listStatus', json.per_status);
            })
            .catch(() => AdminCrud.showToast('Gagal memuat data rekap.', 'danger'));
    }

    form.addEventListener('submit', function (e) {
        // Tombol export (formaction sendiri) dibiarkan submit native -> unduhan file via POST.
        if (e.submitter && e.submitter.hasAttribute('formaction')) return;
        e.preventDefault();
        muatData();
    });

    muatData();
});
