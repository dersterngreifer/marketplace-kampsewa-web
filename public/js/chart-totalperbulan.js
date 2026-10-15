function initChartTotalPerbulan() {
    const canvas = document.getElementById("penghasilan-perbulan");
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

    const labels = window.penghasilanPerbulanLabels || ["Jul", "Agu", "Sep", "Okt"];
    const values = window.penghasilanPerbulanData || [30435142, 25234134, 30345123, 50456250];

    function createGradientPenghasilanPerbulan() {
        const ctx = canvas.getContext("2d");
        const gradient = ctx.createLinearGradient(0, 0, 0, 70);
        gradient.addColorStop(0, "rgb(124,169,207)");
        gradient.addColorStop(1, "rgba(8,14,46,1)");
        return gradient;
    }

    const dataPenghasilanPerbulan = {
        labels: labels,
        datasets: [
            {
                label: "Total Bulanan",
                data: values,
                fill: true,
                borderWidth: 3,
                borderColor: "rgb(124,169,207)",
                tension: 0.4,
                backgroundColor: createGradientPenghasilanPerbulan(),
            },
        ],
    };

    const configPenghasilanPerbulan = {
        type: "line",
        data: dataPenghasilanPerbulan,
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
                    ticks: { color: 'white' },
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

    new Chart(canvas, configPenghasilanPerbulan);
}

window.initChartTotalPerbulan = initChartTotalPerbulan;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initChartTotalPerbulan);
} else {
    initChartTotalPerbulan();
}
