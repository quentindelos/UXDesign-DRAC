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

//Ajax pour le graphique de température
document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour récupérer les données et dessiner le graphique
    function fetchDataAndRenderChart() {
        fetch('src/includes/getLogs-Temp.php')
            .then(response => response.json())
            .then(data => {
                // Récupération du contexte du canvas
                var ctx = document.getElementById('temperatureChart').getContext('2d');
                
                // Mise à jour du graphique existant ou création d'un nouveau
                if(window.myChart) {
                    // Mise à jour des données du graphique existant
                    window.myChart.data.labels = data.map(item => item.time);
                    window.myChart.data.datasets[0].data = data.map(item => item.value);
                    window.myChart.update(); // Mettre à jour le graphique
                } else {
                    // Création d'un nouveau graphique
                    window.myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(item => item.time),
                            datasets: [{
                                label: 'Température',
                                data: data.map(item => item.value),
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                xAxes: [{
                                    type: 'time',
                                    time: {
                                        unit: 'day'
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time'
                                    }
                                }],
                                yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Value'
                                    }
                                }]
                            }
                        }
                    });
                }
            });
    }

    fetchDataAndRenderChart();
    setInterval(fetchDataAndRenderChart, 1000);
});
//Ajax pour le graphique d'humidité
document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour récupérer les données et dessiner le graphique
    function fetchDataAndRenderChart() {
        fetch('src/includes/getLogs-Humidity.php')
            .then(response => response.json())
            .then(data => {
                // Récupération du contexte du canvas
                var ctx = document.getElementById('humidityChart').getContext('2d');
                
                // Mise à jour du graphique existant ou création d'un nouveau
                if(window.myChart) {
                    // Mise à jour des données du graphique existant
                    window.myChart.data.labels = data.map(item => item.time);
                    window.myChart.data.datasets[0].data = data.map(item => item.value);
                    window.myChart.update(); // Mettre à jour le graphique
                } else {
                    // Création d'un nouveau graphique
                    window.myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(item => item.time),
                            datasets: [{
                                label: 'Humidité',
                                data: data.map(item => item.value),
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                xAxes: [{
                                    type: 'time',
                                    time: {
                                        unit: 'day'
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time'
                                    }
                                }],
                                yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Value'
                                    }
                                }]
                            }
                        }
                    });
                }
            });
    }

    fetchDataAndRenderChart();
    setInterval(fetchDataAndRenderChart, 1000);
});
//Ajax pour le graphique d'humidité
document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour récupérer les données et dessiner le graphique
    function fetchDataAndRenderChart() {
        fetch('src/includes/getLogs-CO2.php')
            .then(response => response.json())
            .then(data => {
                // Récupération du contexte du canvas
                var ctx = document.getElementById('co2Chart').getContext('2d');
                
                // Mise à jour du graphique existant ou création d'un nouveau
                if(window.myChart) {
                    // Mise à jour des données du graphique existant
                    window.myChart.data.labels = data.map(item => item.time);
                    window.myChart.data.datasets[0].data = data.map(item => item.value);
                    window.myChart.update(); // Mettre à jour le graphique
                } else {
                    // Création d'un nouveau graphique
                    window.myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(item => item.time),
                            datasets: [{
                                label: 'CO2',
                                data: data.map(item => item.value),
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                xAxes: [{
                                    type: 'time',
                                    time: {
                                        unit: 'day'
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time'
                                    }
                                }],
                                yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Value'
                                    }
                                }]
                            }
                        }
                    });
                }
            });
    }

    fetchDataAndRenderChart();
    setInterval(fetchDataAndRenderChart, 1000);
});