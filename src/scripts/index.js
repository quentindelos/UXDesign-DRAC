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
    function fetchDataAndRenderChart() {
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
    }
    fetchDataAndRenderChart();
    setInterval(fetchDataAndRenderChart, 120000);
});