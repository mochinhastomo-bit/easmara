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

// Form aksi di halaman detail (verifikasi, salurkan, tindak lanjut, tinjauan pimpinan):
// kirim via AJAX, tampilkan toast, lalu muat ulang area detail tanpa refresh halaman.
document.addEventListener('submit', function (e) {
    const form = e.target.closest('form[data-ajax-form]');
    if (!form) return;
    e.preventDefault();

    AdminCrud.ajaxSubmit(form, {
        onSuccess: () => {
            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then((res) => res.text())
                .then((html) => {
                    const fresh = new DOMParser().parseFromString(html, 'text/html').getElementById('detailAspirasi');
                    if (fresh) document.getElementById('detailAspirasi').innerHTML = fresh.innerHTML;
                });
        },
    });
});
