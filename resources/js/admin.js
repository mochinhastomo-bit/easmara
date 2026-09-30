import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// Registrasi eksplisit -- lihat catatan yang sama di resources/js/app.js soal
// import ES module yang di-hoist.
DataTable.use($, 'jq');

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

import * as AdminCrud from './admin-crud';
window.AdminCrud = AdminCrud;

import { Chart, LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler, Tooltip } from 'chart.js';
Chart.register(LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler, Tooltip);
window.Chart = Chart;
