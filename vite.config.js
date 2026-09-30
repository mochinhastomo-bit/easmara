import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss', 'resources/js/app.js',
                'resources/scss/admin.scss', 'resources/js/admin.js',
                'resources/js/pages/admin/dashboard.js',
                'resources/js/pages/admin/simple-master-crud.js',
                'resources/js/pages/admin/aspirasi-index.js',
                'resources/js/pages/admin/aspirasi-show.js',
                'resources/js/pages/admin/audit-log.js',
                'resources/js/pages/admin/banner-konten.js',
                'resources/js/pages/admin/blacklist.js',
                'resources/js/pages/admin/kamus-spam.js',
                'resources/js/pages/admin/media-sosial.js',
                'resources/js/pages/admin/pengaturan.js',
                'resources/js/pages/admin/pengguna.js',
                'resources/js/pages/admin/pesan-kontak.js',
                'resources/js/pages/admin/pimpinan-dprd.js',
                'resources/js/pages/admin/profil-instansi.js',
                'resources/js/pages/admin/prosedur.js',
                'resources/js/pages/admin/rekap.js',
                'resources/js/pages/publik/aspirasi-buat.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
