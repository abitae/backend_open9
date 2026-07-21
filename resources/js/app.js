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

    render('contacts-chart', {
        type: 'bar',
        data: {
            labels: series.labels,
            datasets: [{
                label: 'Contactos',
                data: series.contacts,
                backgroundColor: '#18181b',
                borderRadius: 6,
            }],
        },
        options: chartDefaults,
    });

    render('clients-chart', {
        type: 'bar',
        data: {
            labels: series.labels,
            datasets: [{
                label: 'Clientes',
                data: series.clients,
                backgroundColor: '#7c3aed',
                borderRadius: 6,
            }],
        },
        options: chartDefaults,
    });

    render('orders-chart', {
        type: 'line',
        data: {
            labels: series.labels,
            datasets: [{
                label: 'Pedidos',
                data: series.orders,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 3,
            }],
        },
        options: chartDefaults,
    });
};
