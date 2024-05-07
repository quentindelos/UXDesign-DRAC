// Définir une fonction pour récupérer et afficher les données du capteur
function getDataAndDisplay() {
    $.ajax({
        url: '/ProjetFinal_SNIR/src/includes/GetDataSensor.php',
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

// Exécuter la fonction au chargement du document
$(document).ready(function() {
    // Appeler la fonction une première fois au chargement
    getDataAndDisplay();

    // Planifier l'exécution de la fonction toutes les deux minutes (120000 ms)
    setInterval(function() {
        getDataAndDisplay();
    }, 120000); // 120000 ms = 2 minutes
});
