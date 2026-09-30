/**
 * Helper bersama untuk pola Modal + Toast + AJAX (tanpa reload halaman)
 * dipakai di semua halaman CRUD admin (M5/M6/M7).
 */

export function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const bg = { success: 'text-bg-success', danger: 'text-bg-danger', warning: 'text-bg-warning' }[type]
        || 'text-bg-success';
    const id = 'toast-' + Date.now();

    container.insertAdjacentHTML('beforeend', `
        <div id="${id}" class="toast align-items-center ${bg} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);

    const el = document.getElementById(id);
    const toast = new window.bootstrap.Toast(el, { delay: 4000 });
    toast.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}

function clearFormErrors(form) {
    form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach((el) => { el.textContent = ''; });
}

function showFormErrors(form, errors) {
    Object.entries(errors).forEach(([field, messages]) => {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input) return;
        input.classList.add('is-invalid');
        const feedback = input.closest('.mb-2, .mb-3, .form-check')?.querySelector('.invalid-feedback');
        if (feedback) feedback.textContent = messages[0];
    });
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

/**
 * Submit form via AJAX. Method PUT/DELETE ikut dispoof lewat field `_method`
 * (form tetap kirim POST asli -- ini pola resmi Laravel, aman untuk multipart/file upload).
 */
export function ajaxSubmit(form, { onSuccess } = {}) {
    clearFormErrors(form);
    const formData = new FormData(form);
    if (!formData.get('_method')) formData.delete('_method');

    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
        body: formData,
    })
        .then(async (res) => {
            const json = await res.json().catch(() => ({}));
            if (res.ok) {
                const modalEl = form.closest('.modal');
                if (modalEl) window.bootstrap.Modal.getInstance(modalEl)?.hide();
                showToast(json.message || 'Berhasil disimpan.');
                onSuccess?.(json);
            } else if (res.status === 422) {
                showFormErrors(form, json.errors || {});
            } else {
                showToast(json.message || 'Terjadi kesalahan.', 'danger');
            }
        })
        .catch(() => showToast('Gagal menghubungi server.', 'danger'));
}

export function ajaxDelete(url, { onSuccess, confirmMessage } = {}) {
    if (!window.confirm(confirmMessage || 'Yakin ingin menghapus data ini?')) return;

    const body = new FormData();
    body.append('_method', 'DELETE');

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
        body,
    })
        .then(async (res) => {
            const json = await res.json().catch(() => ({}));
            if (res.ok) {
                showToast(json.message || 'Berhasil dihapus.');
                onSuccess?.(json);
            } else {
                showToast(json.message || 'Gagal menghapus data.', 'danger');
            }
        })
        .catch(() => showToast('Gagal menghubungi server.', 'danger'));
}

/**
 * AJAX POST generik non-form (mis. tombol setujui/tolak) yang mengharap JSON balik.
 */
export function ajaxAction(url, { method = 'POST', data = {}, onSuccess, confirmMessage } = {}) {
    if (confirmMessage && !window.confirm(confirmMessage)) return;

    const body = new FormData();
    Object.entries(data).forEach(([k, v]) => body.append(k, v));
    if (method !== 'POST') body.append('_method', method);

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
        body,
    })
        .then(async (res) => {
            const json = await res.json().catch(() => ({}));
            if (res.ok) {
                showToast(json.message || 'Berhasil.');
                onSuccess?.(json);
            } else {
                showToast(json.message || 'Terjadi kesalahan.', 'danger');
            }
        })
        .catch(() => showToast('Gagal menghubungi server.', 'danger'));
}
