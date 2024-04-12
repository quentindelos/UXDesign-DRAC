$(document).ready(function() {
    setInterval(function() {
        $.ajax({
            url: '../includes/GetDataSensor.php',
            type: 'GET',
            success: function(data) {
                var values = data.split(',');
                $('#temperature').html('Température actuelle : ' + values[0] + ' °C');
                $('#humidity').html("Taux d'humidité actuel :" + values[1] + ' %');
                $('#CO2').html('CO2 actuel : ' + values[2] +  ' ppm');
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la récupération des données: ' + error);
            }
        });
    }, 1000); //temps en ms
});
