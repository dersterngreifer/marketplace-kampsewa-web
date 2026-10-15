function initChartTotalPerminggu() {
    const canvas = document.getElementById("penghasilan-perminggu");
    if (!canvas) {
        return;
    }

    if (typeof Chart === 'undefined') {
        return;
    }

    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
        existingChart.destroy();
    }

    const labels = ["Min 1", "Min 2", "Min 3", "Min 4"];
    const values = window.penghasilanPermingguData || [37435142, 80234134, 38345123, 50456250];

    function createGradientPenghasilanPerminggu() {
        const ctx = canvas.getContext("2d");
        const gradient = ctx.createLinearGradient(0, 0, 0, 70);
        gradient.addColorStop(0, "rgb(8,14,46)");
        gradient.addColorStop(1, "rgba(199,180,238,1)");
        return gradient;
    }

    const dataPenghasilanPerminggu = {
        labels: labels,
        datasets: [
            {
                label: "Total Perminggu",
                data: values,
                fill: true,
                borderWidth: 3,
                borderColor: "rgb(8,14,46)",
                tension: 0.4,
                backgroundColor: createGradientPenghasilanPerminggu(),
            },
        ],
    };

    const configPenghasilanPerminggu = {
        type: "line",
        data: dataPenghasilanPerminggu,
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
                            if (context.parsed.y !== null) {
                                return 'Rp. ' + Number(context.parsed.y).toLocaleString('id-ID');
                            }
                            return '';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: 'rgb(8,14,46)' },
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

    new Chart(canvas, configPenghasilanPerminggu);
}

window.initChartTotalPerminggu = initChartTotalPerminggu;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initChartTotalPerminggu);
} else {
    initChartTotalPerminggu();
}
