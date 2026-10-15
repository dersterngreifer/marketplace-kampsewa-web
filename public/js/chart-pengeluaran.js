function createGradientPengeluaranPertahun(id) {
    const el = document.getElementById(id);
    if (!el) return 'rgb(179, 129, 244)';
    const ctx = el.getContext("2d");
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, "rgb(179, 129, 244)");
    gradient.addColorStop(1, "rgb(80, 56, 237)");
    return gradient;
}

const createGradientChartLine = (id) => {
    const el = document.getElementById(id);
    if (!el) return 'rgba(255, 255, 255, 0)';
    const ctx = el.getContext("2d");
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, "rgb(179, 129, 244)");
    gradient.addColorStop(1, "rgba(255, 255, 255, 0)");
    return gradient;
}

function initPengeluaranPertahunChart() {
    const canvasIDPengeluaran = document.getElementById("chart-pengeluaran-pertahun");
    if (!canvasIDPengeluaran || typeof Chart === 'undefined') return;

    const existing = Chart.getChart(canvasIDPengeluaran);
    if (existing) existing.destroy();

    const labelsPengeluaranPertahun = [
        "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
        "Jul", "Ag", "Sep", "Okt", "Nov", "Des",
    ];
    const dataPengeluaranPertahun = {
        labels: labelsPengeluaranPertahun,
        datasets: [
            {
                label: "Total Pengeluaran",
                data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56],
                borderRadius: 10,
                borderWidth: 0,
                backgroundColor: createGradientPengeluaranPertahun('chart-pengeluaran-pertahun'),
                borderColor: 'rgb(124,169,207)',
                borderSkipped: false,
                barPercentage: 1,
                hoverBackgroundColor: "rgb(235,235,235)",
            },
        ],
    };

    const configPengeluaranPertahun = {
        type: "bar",
        data: dataPengeluaranPertahun,
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                },
                y: {
                    ticks: { display: false },
                    beginAtZero: true,
                    border: { display: false },
                    grid: { display: false },
                },
            },
        },
    };
    new Chart(canvasIDPengeluaran, configPengeluaranPertahun);
}

function initPengeluaranPerbulanChart() {
    const canvasIDPengeluaranPerbulan = document.getElementById("chart-pengeluaran-perbulan");
    if (!canvasIDPengeluaranPerbulan || typeof Chart === 'undefined') return;

    const existing = Chart.getChart(canvasIDPengeluaranPerbulan);
    if (existing) existing.destroy();

    const labelsPengeluaranPerbulan = [
        "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
        "Jul", "Ag", "Sep", "Okt", "Nov", "Des"
    ];
    const dataPengeluaranPerbuulan = {
        labels: labelsPengeluaranPerbulan,
        datasets: [
            {
                label: "Total Pengeluaran Perbulan",
                data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56],
                borderWidth: 3,
                borderColor: createGradientPengeluaranPertahun('chart-pengeluaran-perbulan'),
                tension: 0.4,
                fill: true,
                backgroundColor: createGradientChartLine('chart-pengeluaran-perbulan'),
            },
        ],
    };
    const configPengeluaranPerbulan = {
        type: "line",
        data: dataPengeluaranPerbuulan,
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            if (value !== 0) return value;
                        },
                    },
                    border: { display: false },
                    grid: { display: false }
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                }
            },
            plugins: {
                legend: { display: false }
            }
        },
    };
    new Chart(canvasIDPengeluaranPerbulan, configPengeluaranPerbulan);
}

window.initPengeluaranPertahunChart = initPengeluaranPertahunChart;
window.initPengeluaranPerbulanChart = initPengeluaranPerbulanChart;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
        initPengeluaranPertahunChart();
        initPengeluaranPerbulanChart();
    });
} else {
    initPengeluaranPertahunChart();
    initPengeluaranPerbulanChart();
}
