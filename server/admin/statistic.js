document.addEventListener('DOMContentLoaded', async function () {

    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["2022-01", "2022-02", "2022-03", "2022-04", "2022-05", "2022-06", "2022-07", "2022-08", "2022-09", "2022-10", "2022-11", "2022-12"],
            datasets: [{
                label: 'New Offers',
                data: [],
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 3,
                fill: false
            },
            {
                label: 'Processed Offers',
                data: [],
                borderColor: 'rgba(255, 205, 86, 1)',
                borderWidth: 3,
                fill: false
            },
            {
                label: 'New Requests',
                data: [],
                borderColor: 'rgba(70, 255, 100, 1)',
                borderWidth: 3,
                fill: false
            },
            {
                label: 'Requests Processed',
                data: [],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 3,
                fill: false
            }]
        },
        options: {
            scales: {
                x: [{
                    type: 'time',
                    time: {
                        unit: 'month',
                    }
                }],
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    document.getElementById('updateChart').addEventListener('click', async function () {

        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;

        console.log('startDate:', startDate);
        console.log('endDate:', endDate);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {

                var data = JSON.parse(xhr.responseText);

                var start = luxon.DateTime.fromISO(startDate);
                var end = luxon.DateTime.fromISO(endDate);
                var current = start;
                var monthLabels = [];

                while (current <= end) {
                    monthLabels.push(current.toFormat('yyyy-MM'));
                    current = current.plus({ months: 1 });
                }

                myChart.data.labels = monthLabels;
                myChart.data.datasets[0].data = data.newOffers;
                myChart.data.datasets[1].data = data.processedOffers;
                myChart.data.datasets[2].data = data.newRequests;
                myChart.data.datasets[3].data = data.proccesedRequests;

                myChart.update();
            }
        };

        xhr.open('GET', 'getChartData.php?startDate=' + startDate + '&endDate=' + endDate, true);
        xhr.send();
    });

    await updateChart();

    async function updateChart() {

        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                console.log('Valor de newOffers:', data);

                myChart.data.datasets[0].data = data.newOffers;
                myChart.data.datasets[1].data = data.processedOffers;
                myChart.data.datasets[2].data = data.newRequests;
                myChart.data.datasets[3].data = data.processedRequests;
                myChart.update();
            }
        };
        
        xhr.open('GET', 'getChartData.php?startDate=' + startDate + '&endDate=' + endDate, true);
        xhr.send();
    }
});
