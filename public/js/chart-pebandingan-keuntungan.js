/*
/---------------------------------/
/- chart perbandingan keuntungan pertahun -/
/---------------------------------/
*/
function initChartPerbandinganKeuntungan() {
    const canvas = document.getElementById("pebandingan-keuntungan-pertahun");
    if (!canvas) {
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded.');
        return;
    }

    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
        existingChart.destroy();
    }

    const labelsPerbandinganKeuntunganPertahun = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Ag",
        "Sep",
        "Okt",
        "Nov",
        "Des"
    ];

    const defaultDatasets = [
        {
            label: "Tahun 2026",
            data: [42.4, 29.0, 29.3, 26.5, 35.4, 39.9, 37.6, 63.0, 97.6, 133.5, 0, 0],
            borderWidth: 3,
            borderColor: "rgb(40, 127, 113)", // #287f71
            tension: 0.4,
            fill: false,
        },
        {
            label: "Tahun 2025",
            data: [29.0, 34.4, 33.0, 28.9, 33.2, 26.5, 30.3, 36.2, 29.3, 27.1, 32.2, 43.4],
            borderWidth: 3,
            borderColor: "rgb(235, 134, 43)", // #EB862B
            tension: 0.4,
            fill: false,
        },
        {
            label: "Tahun 2024",
            data: [20.2, 20.4, 18.9, 23.4, 22.3, 17.2, 28.9, 23.4, 21.0, 20.8, 24.7, 22.1],
            borderWidth: 3,
            borderColor: "rgb(179, 129, 244)", // #B381F4
            tension: 0.4,
            fill: false,
        },
        {
            label: "Tahun 2023",
            data: [18.8, 23.0, 17.7, 9.8, 17.6, 17.8, 15.6, 16.1, 16.7, 24.7, 22.2, 20.3],
            borderWidth: 3,
            borderColor: "rgb(207, 245, 0)", // #CFF500
            tension: 0.4,
            fill: false,
        },
    ];

    const datasets = window.rekapKeuntunganChartData || defaultDatasets;

    const dataPerbandinganPenghasilanPertahun = {
        labels: labelsPerbandinganKeuntunganPertahun,
        datasets: datasets,
    };

    const configPerbandinganKeuntunganPertahun = {
        type: "line",
        data: dataPerbandinganPenghasilanPertahun,
        options: {
            responsive: true,
            maintainAspectRatio: true,
            layout: {
                padding: { left: 0, right: 0, top: 0, bottom: 0 },
            },
            plugins: {
                legend: {
                    display: false, // Tidak dikecilkan, menggunakan header card stat sebagai legend
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID') + ' Jt';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return `${value} M`;
                        }
                    },
                    border: {
                        display: false,
                    },
                    grid: {
                        display: false,
                    }
                },
                x: {
                    grid: {
                        display: true,
                    },
                    border: {
                        display: false,
                    },
                }
            },
        },
    };

    new Chart(canvas, configPerbandinganKeuntunganPertahun);
}

window.initChartPerbandinganKeuntungan = initChartPerbandinganKeuntungan;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initChartPerbandinganKeuntungan);
} else {
    initChartPerbandinganKeuntungan();
}
