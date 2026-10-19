/*
/----------------------------------------
/- customer chart pemasukan tahun saat ini dashboard
/- id canvas = customer-chart-pemasukan-dsb
/----------------------------------------
*/

document.addEventListener("DOMContentLoaded", function () {
    // Labels 12 Bulan
    const labelsMonthDashboard = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agu",
        "Sep",
        "Okt",
        "Nov",
        "Des",
    ];

    // Data Pemasukan Pertahun (Line Chart)
    const dataPemasukanTahunDashboard = {
        labels: labelsMonthDashboard,
        datasets: [
            {
                label: "Total Tahun " + (window.labelTahunLalu || "Lalu"),
                data: window.chartTahunLalu || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                fill: false,
                borderColor: "rgb(86,11,208)",
                backgroundColor: "rgba(86,11,208,0.1)",
                borderWidth: 3,
                tension: 0.4,
            },
            {
                label: "Total Tahun " + (window.labelTahunIni || "Ini"),
                data: window.chartTahunIni || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                fill: false,
                borderColor: "rgb(3,118,253)",
                backgroundColor: "rgba(3,118,253,0.1)",
                borderWidth: 3,
                tension: 0.4,
            },
        ],
    };

    // Config Line Chart
    const configPemasukanTahunDashboard = {
        type: "line",
        data: dataPemasukanTahunDashboard,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let val = context.parsed.y || 0;
                            return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: {
                        display: false,
                    },
                    grid: {
                        display: true,
                        color: "rgba(0,0,0,0.05)",
                    },
                    ticks: {
                        callback: function (value) {
                            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            return 'Rp ' + value;
                        }
                    }
                },
                x: {
                    border: {
                        display: false,
                    },
                    grid: {
                        display: false,
                    },
                },
            },
        },
    };

    // Render Line Chart
    const ctx = document.getElementById("customer-chart-pemasukan-dsb");
    if (ctx) {
        new Chart(ctx, configPemasukanTahunDashboard);
    }

    /*
    /----------------------------------------
    /- customer chart pemasukan perbulan dashboard (Bar Chart)
    /- id canvas = customer-chart-pemasukan-perbulan-dsb
    /----------------------------------------
    */
    const dataPemasukanBulanDashboard = {
        labels: labelsMonthDashboard,
        datasets: [
            {
                label: "Total Tahun " + (window.labelTahunLalu || "Lalu"),
                data: window.chartTahunLalu || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                backgroundColor: "rgb(255,206,86)",
                borderColor: "rgb(255,206,86)",
                borderWidth: 1,
                borderRadius: 6,
            },
            {
                label: "Total Tahun " + (window.labelTahunIni || "Ini"),
                data: window.chartTahunIni || [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                backgroundColor: "rgb(75,192,192)",
                borderColor: "rgb(75,192,192)",
                borderWidth: 1,
                borderRadius: 6,
            },
        ],
    };

    const configPemasukanBulanDashboard = {
        type: "bar",
        data: dataPemasukanBulanDashboard,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let val = context.parsed.y || 0;
                            return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: {
                        display: false,
                    },
                    grid: {
                        display: true,
                        color: "rgba(0,0,0,0.05)",
                    },
                    ticks: {
                        callback: function (value) {
                            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            return 'Rp ' + value;
                        }
                    }
                },
                x: {
                    border: {
                        display: false,
                    },
                    grid: {
                        display: false,
                    },
                },
            },
        },
    };

    // Render Bar Chart
    const ctxPerbulan = document.getElementById("customer-chart-pemasukan-perbulan-dsb");
    if (ctxPerbulan) {
        new Chart(ctxPerbulan, configPemasukanBulanDashboard);
    }
});
