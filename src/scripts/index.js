function opentab(tabname) {
    const sensors = document.querySelector(".sensors");
    const historique = document.querySelector(".historique");

    if (tabname === "sensors") {
        sensors.style.zIndex = "1";
        historique.style.zIndex = "0";
    } else if (tabname === "historique") {
        historique.style.zIndex = "1";
        sensors.style.zIndex = "0";
    }
}

//Ajax pour les capteurs
$(document).ready(function() {
    setInterval(function() {
        $.ajax({
            url: 'src/includes/getData.php',
            type: 'GET',
            success: function(data) {
                var values = data.split(',');
                $('#temperature').html('Température actuelle : ' + values[0] + ' °C');
                $('#humidity').html("Taux d'humidité actuel : " + values[1] + ' %');
                $('#CO2').html('CO2 actuel : ' + values[2] + ' ppm');
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la récupération des données: ' + error);
            }
        });
    }, 1000); //temps en ms
});


$(document).ready(function() {
    // Fonction pour récupérer les données de chaque capteur
    function getData(sensor, chartId) {
        $.ajax({
            url: 'getLogs.php',
            type: 'POST',
            data: { sensor: sensor },
            success: function(data) {
                var values = JSON.parse(data);

                // Créer le graphique
                var ctx = document.getElementById(chartId).getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: values.labels,
                        datasets: [{
                            label: 'Valeurs',
                            data: values.data,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la récupération des données: ' + error);
            }
        });
    }

    // Récupérer les données pour chaque capteur et créer les graphiques
    getData('temperature', 'temperatureChart');
    getData('humidity', 'humidityChart');
    getData('co2', 'co2Chart');
});