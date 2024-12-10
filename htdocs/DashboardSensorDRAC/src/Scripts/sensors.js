function getDataAndDisplay() {
    $.ajax({
        url: '/DashboardSensorDRAC/src/Scripts/GetDataSensor.php',
        type: 'GET',
        success: function(data) {
            var values = data.split(',');
            var temperature = parseFloat(values[0]);
            var humidity = parseFloat(values[1]);
            var co2 = parseFloat(values[2]);

            $('#temperature').html('Température actuelle : ' + temperature + ' °C');
            $('#humidity').html("Taux d'humidité actuel : " + humidity + ' %');
            $('#CO2').html('CO2 actuel : ' + co2 + ' ppm');

            google.charts.load('current', {'packages':['gauge']});
            google.charts.setOnLoadCallback(function() {
                drawChart(temperature, humidity, co2);
            });
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de la récupération des données: ' + error);
        }
    });
}

function drawChart(temperature, humidity, co2) {
    var tempData = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['°C', temperature]
    ]);
    var optionsTemp = {
        width: 600,
        height: 200,
        greenFrom: 15,
        greenTo:30,
        yellowFrom: 30,
        yellowTo: 32,
        redFrom: 32,
        redTo: 35,
        minorTicks: 10,
        min: 15,
        max: 35
    };

    var humData = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['%', humidity]
    ]);
    var optionsHum = {
        width: 600,
        height: 200,
        greenFrom: 0,
        greenTo:75,
        yellowFrom: 75,
        yellowTo: 90,
        redFrom: 90,
        redTo: 100,
        minorTicks: 10,
        min: 0,
        max: 100
    };

    var co2Data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['PPM', co2]
    ]);
    var optionsCO2 = {
        width: 600,
        height: 200,
        greenFrom: 400,
        greenTo:700,
        yellowFrom: 700,
        yellowTo: 800,
        redFrom: 800,
        redTo: 1200,
        minorTicks: 10,
        min: 400,
        max: 1200
    };

    var tempChart = new google.visualization.Gauge(document.getElementById('temp_div'));
    var humChart = new google.visualization.Gauge(document.getElementById('hum_div'));
    var co2Chart = new google.visualization.Gauge(document.getElementById('co2_div'));

    tempChart.draw(tempData, optionsTemp);
    humChart.draw(humData, optionsHum);
    co2Chart.draw(co2Data, optionsCO2);
}

$(document).ready(function() {
    // Appeler la fonction une première fois au chargement
    getDataAndDisplay();

    // Planifier l'exécution de la fonction toutes les deux minutes (120000 ms)
    setInterval(function() {
        getDataAndDisplay();
    }, 30000); // 30000 ms = 30 secondes
});