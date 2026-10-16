<?php

use App\Http\Controllers\Admin\AspirasiAdminController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BannerKontenController;
use App\Http\Controllers\Admin\BlacklistNomorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KamusSpamController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KomisiController;
use App\Http\Controllers\Admin\MediaSosialController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PengaturanSistemController;
use App\Http\Controllers\Admin\PesanKontakController;
use App\Http\Controllers\Admin\PimpinanDprdController;
use App\Http\Controllers\Admin\ProfilInstansiController;
use App\Http\Controllers\Admin\ProsedurLayananController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\VerifikasiWaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Publik\AspirasiFormController;
use App\Http\Controllers\Publik\AspirasiStatusController;
use App\Http\Controllers\Publik\BerandaController;
use App\Http\Controllers\Publik\KontakController;
use App\Http\Controllers\Publik\OtpController;
use App\Http\Controllers\Publik\ProfilController;
use App\Http\Controllers\Publik\ProsedurController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'show'])->name('beranda');
Route::get('/profil', [ProfilController::class, 'show'])->name('profil');
Route::get('/prosedur', [ProsedurController::class, 'show'])->name('prosedur');
Route::get('/kontak', [KontakController::class, 'show'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store')->middleware('throttle:10,1');

Route::prefix('aspirasi')->name('aspirasi.')->middleware('throttle:30,1')->group(function () {
    Route::get('/buat', [AspirasiFormController::class, 'create'])->name('buat');
    Route::post('/buat', [AspirasiFormController::class, 'store'])->name('store');
    Route::post('/wilayah/{kecamatan}/desa', [AspirasiFormController::class, 'desaByKecamatan'])->name('wilayah.desa');
    Route::get('/berhasil', [AspirasiFormController::class, 'berhasil'])->name('berhasil');

    Route::get('/verifikasi-hp', [OtpController::class, 'showKirim'])->name('verifikasi-hp');
    Route::post('/verifikasi-hp', [OtpController::class, 'kirim'])->name('verifikasi-hp.kirim');
    Route::get('/verifikasi-otp', [OtpController::class, 'showVerifikasi'])->name('verifikasi-otp');
    Route::post('/verifikasi-otp', [OtpController::class, 'verifikasi'])->name('verifikasi-otp.submit');

    Route::get('/status', [AspirasiStatusController::class, 'form'])->name('status');
    Route::post('/status', [AspirasiStatusController::class, 'cari'])->name('status.cari');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('aspirasi')->name('aspirasi.')->group(function () {
        Route::get('/', [AspirasiAdminController::class, 'index'])->name('index');
        Route::post('/data', [AspirasiAdminController::class, 'data'])->name('data');
        Route::get('/{aspirasi}', [AspirasiAdminController::class, 'show'])->name('show');
        Route::post('/{aspirasi}/verifikasi', [AspirasiAdminController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/{aspirasi}/salurkan', [AspirasiAdminController::class, 'salurkan'])->name('salurkan');
        Route::post('/{aspirasi}/tindak-lanjut', [AspirasiAdminController::class, 'updateTindakLanjut'])->name('tindak-lanjut');
        Route::post('/{aspirasi}/pimpinan', [AspirasiAdminController::class, 'updatePimpinan'])->name('pimpinan');
    });

    Route::prefix('rekap')->name('rekap.')->group(function () {
        Route::get('/', [RekapController::class, 'index'])->name('index');
        Route::post('/data', [RekapController::class, 'data'])->name('data');
        Route::post('/export-excel', [RekapController::class, 'exportExcel'])->name('export-excel');
        Route::post('/export-pdf', [RekapController::class, 'exportPdf'])->name('export-pdf');
    });

    Route::get('/verifikasi-wa', [VerifikasiWaController::class, 'index'])->name('verifikasi-wa.index');

    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
    Route::post('/audit-log/data', [AuditLogController::class, 'data'])->name('audit-log.data');

    foreach ([
        'kategori' => KategoriController::class,
        'komisi' => KomisiController::class,
        'media-sosial' => MediaSosialController::class,
        'prosedur' => ProsedurLayananController::class,
        'profil-instansi' => ProfilInstansiController::class,
        'pimpinan-dprd' => PimpinanDprdController::class,
        'banner-konten' => BannerKontenController::class,
    ] as $slug => $controller) {
        Route::prefix($slug)->name("{$slug}.")->group(function () use ($controller) {
            Route::get('/', [$controller, 'index'])->name('index');
            Route::post('/data', [$controller, 'data'])->name('data');
            Route::post('/', [$controller, 'store'])->name('store');
            Route::put('/{id}', [$controller, 'update'])->name('update');
            Route::delete('/{id}', [$controller, 'destroy'])->name('destroy');
        });
    }

    Route::prefix('pesan-kontak')->name('pesan-kontak.')->group(function () {
        Route::get('/', [PesanKontakController::class, 'index'])->name('index');
        Route::post('/data', [PesanKontakController::class, 'data'])->name('data');
        Route::post('/{id}/detail', [PesanKontakController::class, 'show'])->name('show');
        Route::put('/{id}/status', [PesanKontakController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [PesanKontakController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('kamus-spam')->name('kamus-spam.')->group(function () {
        Route::get('/', [KamusSpamController::class, 'index'])->name('index');
        Route::post('/data', [KamusSpamController::class, 'data'])->name('data');
        Route::post('/', [KamusSpamController::class, 'store'])->name('store');
        Route::put('/{id}', [KamusSpamController::class, 'update'])->name('update');
        Route::delete('/{id}', [KamusSpamController::class, 'destroy'])->name('destroy');
        Route::post('/usulan/data', [KamusSpamController::class, 'usulanData'])->name('usulan.data');
        Route::post('/usulan/{id}/setujui', [KamusSpamController::class, 'setujuiUsulan'])->name('usulan.setujui');
        Route::post('/usulan/{id}/tolak', [KamusSpamController::class, 'tolakUsulan'])->name('usulan.tolak');
    });

    Route::prefix('blacklist')->name('blacklist.')->group(function () {
        Route::get('/', [BlacklistNomorController::class, 'index'])->name('index');
        Route::post('/data', [BlacklistNomorController::class, 'data'])->name('data');
        Route::post('/', [BlacklistNomorController::class, 'store'])->name('store');
        Route::delete('/{id}', [BlacklistNomorController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::get('/', [PenggunaController::class, 'index'])->name('index');
        Route::post('/data', [PenggunaController::class, 'data'])->name('data');
        Route::post('/', [PenggunaController::class, 'store'])->name('store');
        Route::put('/{id}', [PenggunaController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenggunaController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/reset-password', [PenggunaController::class, 'resetPassword'])->name('reset-password');
    });

    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [PengaturanSistemController::class, 'index'])->name('index');
        Route::post('/data', [PengaturanSistemController::class, 'data'])->name('data');
        Route::post('/', [PengaturanSistemController::class, 'store'])->name('store');
        Route::put('/{id}', [PengaturanSistemController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengaturanSistemController::class, 'destroy'])->name('destroy');
    });
});
