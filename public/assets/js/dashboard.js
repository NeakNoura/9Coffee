// Match chart height to table
function matchChartHeight() {
    const tableCard = document.getElementById('recentOrdersCard');
    const chartCard = document.getElementById('analyticsCard');
    if (tableCard && chartCard) chartCard.style.height = tableCard.offsetHeight + 'px';
}

window.addEventListener('load', matchChartHeight);
window.addEventListener('resize', matchChartHeight);

// Chart.js Analytics
const ctx = document.getElementById('analyticsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['SaleTotal', 'Orders', 'Expense', 'Earnings'],
        datasets: [{
            label: 'Statistics',
            data: [window.totalSales ?? 0, window.ordersCount ?? 0, window.totalExpenses ?? 0, window.earning ?? 0],
            backgroundColor: ['rgba(54,162,235,0.7)','rgba(255,99,132,0.7)','rgba(255,206,86,0.7)','rgba(75,192,192,0.7)'],
            borderColor: ['rgba(54,162,235,1)','rgba(255,99,132,1)','rgba(255,206,86,1)','rgba(75,192,192,1)'],
            borderWidth: 1,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true },
            x: { grid: { display: false } }
        }
    }
});
