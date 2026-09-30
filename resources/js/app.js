import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// Registrasi eksplisit -- JANGAN andalkan auto-detect via window.jQuery:
// import ES module di-hoist, jadi assignment window.jQuery = $ baru jalan
// SETELAH semua import (termasuk datatables.net) selesai dievaluasi, sudah
// terlambat untuk auto-detection internalnya.
DataTable.use($, 'jq');

// Bahasa Indonesia default untuk semua tabel DataTables di aplikasi ini
DataTable.defaults.language = {
    search: 'Cari:',
    lengthMenu: 'Tampilkan _MENU_ data',
    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
    infoEmpty: 'Tidak ada data',
    infoFiltered: '(disaring dari _MAX_ total data)',
    zeroRecords: 'Data tidak ditemukan',
    emptyTable: 'Tidak ada data yang tersedia',
    loadingRecords: 'Memuat&hellip;',
    processing: 'Memuat data&hellip;',
    paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' },
};

window.$ = window.jQuery = $;
window.DataTable = DataTable;

import './recaptcha';

import * as AdminCrud from './admin-crud';
window.AdminCrud = AdminCrud;
