document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('chartTren');
    if (!canvas) return;

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: JSON.parse(canvas.dataset.labels),
            datasets: [{
                label: 'Aspirasi Masuk',
                data: JSON.parse(canvas.dataset.values),
                borderColor: '#ffffff',
                backgroundColor: 'rgba(255, 255, 255, 0.15)',
                fill: true,
                tension: 0.3,
                pointRadius: 3,
                pointBackgroundColor: '#ffffff',
            }],
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: 'rgba(255,255,255,0.85)' },
                    grid: { color: 'rgba(255,255,255,0.15)' },
                },
                x: {
                    ticks: { color: 'rgba(255,255,255,0.85)' },
                    grid: { color: 'rgba(255,255,255,0.15)' },
                },
            },
        },
    });
});
