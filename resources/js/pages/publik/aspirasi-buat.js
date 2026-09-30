document.getElementById('kecamatan_select').addEventListener('change', function () {
    const wilayahSelect = document.getElementById('wilayah_select');
    const kecamatanId = this.value;

    wilayahSelect.disabled = true;
    wilayahSelect.innerHTML = '<option value="">Memuat&hellip;</option>';

    if (!kecamatanId) {
        wilayahSelect.innerHTML = '<option value="">Pilih kecamatan dahulu&hellip;</option>';
        return;
    }

    fetch(`/aspirasi/wilayah/${kecamatanId}/desa`)
        .then((res) => res.json())
        .then((data) => {
            wilayahSelect.innerHTML = '<option value="">Pilih desa/kelurahan&hellip;</option>';
            data.forEach((w) => {
                const opt = document.createElement('option');
                opt.value = w.id;
                opt.textContent = `${w.nama} (${w.jenis})`;
                wilayahSelect.appendChild(opt);
            });
            wilayahSelect.disabled = false;
        });
});
