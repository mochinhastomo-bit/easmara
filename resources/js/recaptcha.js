/**
 * Google reCAPTCHA v3 helper.
 * Form yang butuh proteksi diberi atribut `data-recaptcha-action="nama_aksi"`.
 * Skrip ini akan mengisi <input type="hidden" name="recaptcha_token"> sebelum submit.
 */
const siteKeyMeta = document.querySelector('meta[name="recaptcha-site-key"]');
const siteKey = siteKeyMeta ? siteKeyMeta.content : null;

if (siteKey) {
    const script = document.createElement('script');
    script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
    document.head.appendChild(script);

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.dataset.recaptchaAction) {
            return;
        }
        if (form.dataset.recaptchaVerified === 'true') {
            return;
        }

        event.preventDefault();

        window.grecaptcha.ready(() => {
            window.grecaptcha
                .execute(siteKey, { action: form.dataset.recaptchaAction })
                .then((token) => {
                    let input = form.querySelector('input[name="recaptcha_token"]');
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'recaptcha_token';
                        form.appendChild(input);
                    }
                    input.value = token;
                    form.dataset.recaptchaVerified = 'true';
                    form.submit();
                });
        });
    });
}
