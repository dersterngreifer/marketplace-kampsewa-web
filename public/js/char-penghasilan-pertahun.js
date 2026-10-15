function initPenghasilanPertahunChart() {
    const canvas = document.getElementById("penghasilan");
    if (!canvas) {
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded.');
        return;
    }

    // Destroy previous instance if any
    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
        existingChart.destroy();
    }

    const labelsCadangan = [
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
        "Des",
    ];

    function createGradient() {
        const ctx = canvas.getContext("2d");
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, "rgb(124,169,207)");
        gradient.addColorStop(1, "rgba(255,255,255,0)");
        return gradient;
    }

    function renderChart(dataValues) {
        const dataPenghasilanPertahun = {
            labels: labelsCadangan,
            datasets: [
                {
                    label: "Total Penghasilan",
                    data: dataValues,
                    fill: true,
                    borderWidth: 3,
                    borderColor: "rgb(124,169,207)",
                    tension: 0.4,
                    backgroundColor: createGradient(),
                },
            ],
        };

        const configPenghasilanPertahun = {
            type: "line",
            data: dataPenghasilanPertahun,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                layout: {
                    padding: { left: 0, right: 0, top: 0, bottom: 0 },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp. ' + Number(context.parsed.y).toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                    },
                    y: {
                        ticks: {
                            callback: function (value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toLocaleString('id-ID') + ' Jt';
                                }
                                if (value !== 0) {
                                    return value.toLocaleString('id-ID');
                                }
                                return 0;
                            },
                        },
                        beginAtZero: true,
                        border: { display: false },
                        grid: { display: false },
                    },
                },
            },
        };

        new Chart(canvas, configPenghasilanPertahun);
    }

    if (window.penghasilanPertahunData && Array.isArray(window.penghasilanPertahunData)) {
        renderChart(window.penghasilanPertahunData);
    } else {
        fetch('/api/chart-penghasilan-menu-penghasilan')
            .then(res => res.json())
            .then(data => {
                if (data && data.total_pemasukan_per_bulan) {
                    const monthNames = [
                        "January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];
                    const values = monthNames.map(m => {
                        const found = data.total_pemasukan_per_bulan.find(item => item.month === m);
                        return found ? parseInt(found.total, 10) : 0;
                    });
                    renderChart(values);
                }
            })
            .catch(err => {
                console.error('Error fetching penghasilan chart data:', err);
            });
    }
}

window.initPenghasilanPertahunChart = initPenghasilanPertahunChart;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPenghasilanPertahunChart);
} else {
    initPenghasilanPertahunChart();
}
