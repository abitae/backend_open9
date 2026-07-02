import Chart from 'chart.js/auto';

window.open9Charts = window.open9Charts || {};

window.renderOpen9DashboardCharts = (series) => {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            },
        },
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                },
            },
        },
    };

    const render = (id, config) => {
        const canvas = document.getElementById(id);

        if (!canvas) {
            return;
        }

        window.open9Charts[id]?.destroy();
        window.open9Charts[id] = new Chart(canvas, config);
    };

    render('monthly-enrollments-chart', {
        type: 'bar',
        data: {
            labels: series.labels,
            datasets: [{
                label: 'Inscripciones',
                data: series.enrollments,
                backgroundColor: '#18181b',
                borderRadius: 6,
            }],
        },
        options: chartDefaults,
    });

    render('approved-payments-chart', {
        type: 'bar',
        data: {
            labels: series.labels,
            datasets: [{
                label: 'Pagos aprobados',
                data: series.payments,
                backgroundColor: '#059669',
                borderRadius: 6,
            }],
        },
        options: chartDefaults,
    });

    render('revenue-chart', {
        type: 'line',
        data: {
            labels: series.labels,
            datasets: [{
                label: 'Ingresos',
                data: series.revenue,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 3,
            }],
        },
        options: {
            ...chartDefaults,
            scales: {
                ...chartDefaults.scales,
                y: {
                    ...chartDefaults.scales.y,
                    ticks: {
                        callback: (value) => `S/ ${value}`,
                    },
                },
            },
        },
    });
};
