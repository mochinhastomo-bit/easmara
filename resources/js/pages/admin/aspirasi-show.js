document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalLampiran');
    const modal = new bootstrap.Modal(modalEl);
    const imgEl = document.getElementById('lampiranGambar');
    const pdfEl = document.getElementById('lampiranPdf');
    const bukaBaruEl = document.getElementById('lampiranBukaBaru');

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-lihat-lampiran');
        if (!btn) return;

        imgEl.style.display = 'none';
        pdfEl.style.display = 'none';
        imgEl.src = '';
        pdfEl.src = '';

        if (btn.dataset.tipe === 'gambar') {
            imgEl.src = btn.dataset.url;
            imgEl.style.display = 'inline-block';
        } else {
            pdfEl.src = btn.dataset.url;
            pdfEl.style.display = 'block';
        }
        bukaBaruEl.href = btn.dataset.url;
        modal.show();
    });

    // Kosongkan src saat modal ditutup supaya PDF/gambar berhenti dimuat.
    modalEl.addEventListener('hidden.bs.modal', function () {
        imgEl.src = '';
        pdfEl.src = '';
    });
});
