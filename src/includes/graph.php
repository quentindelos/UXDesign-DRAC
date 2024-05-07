<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <title>Graphique des capteurs</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fonction pour mettre à jour le graphique avec les nouvelles données
        function updateChart() {
            // Effectuer une requête AJAX pour obtenir les données
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Conversion des données JSON en objet JavaScript
                        var data = JSON.parse(xhr.responseText);
                        // Mise à jour du graphique
                        chart.data.labels = data.labels;
                        chart.data.datasets[0].data = data.temperature;
                        chart.data.datasets[1].data = data.humidity;
                        chart.data.datasets[2].data = data.co2;
                        chart.update();
                    }
                }
            };
           
        }

        setInterval(updateChart, 2000);
    </script>
</head>
<body>
    <div class="historique">
        <canvas id="graphique"></canvas>
    </div>
    
    <script>
        // Créer un graphique avec Chart.js
        var ctx = document.getElementById('graphique').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Température',
                    data: [],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    lineTension: 0.3,
                }, {
                    label: 'Humidité',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    lineTension: 0.3,
                }, {
                    label: 'CO2',
                    data: [],
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1,
                    lineTension: 0.3,
                }]
            },
            options: {
                scales: {
                    y: {
                        type: 'logarithmic',
                    }
                }
            }
        });
    </script>

<?php
require('accessDB.php');

try {
    // Connexion à la base de données avec PDO
    $dsn = "mysql:host=$host;dbname=$DB_Drac;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Configuration de PDO pour afficher les exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données de la table loratabletemperature
    $sql_temperature = "SELECT * FROM `CAPTEUR` WHERE ID_TYPE_CAPTEUR = 'T' ORDER BY ID_CAPTEUR";
    $stmt_temperature = $pdo->query($sql_temperature);
    $temperature_data = array('TIME' => array(), 'MESURE' => array());

    // Récupérer les données de la table loratablehumidite
    $sql_humidity = "SELECT * FROM `CAPTEUR` WHERE ID_TYPE_CAPTEUR = 'H' ORDER BY ID_CAPTEUR";
    $stmt_humidity = $pdo->query($sql_humidity);
    $humidity_data = array('TIME' => array(), 'MESURE' => array());

    // Récupérer les données de la table loratableco2
    $sql_co2 = "SELECT * FROM `CAPTEUR` WHERE ID_TYPE_CAPTEUR = 'C' ORDER BY ID_CAPTEUR";
    $stmt_co2 = $pdo->query($sql_co2);
    $co2_data = array('TIME' => array(), 'MESURE' => array());

    // Parcourir les résultats et les ajouter aux tableaux de données respectifs
    function fetch_data($stmt, &$data) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data['TIME'][] = $row['TIME'];
            $data['MESURE'][] = $row['MESURE'];
        }
    }

    fetch_data($stmt_temperature, $temperature_data);
    fetch_data($stmt_humidity, $humidity_data);
    fetch_data($stmt_co2, $co2_data);

    // Renvoyer les données au format JSON
    echo "<script>";
    echo "var data = {";
    echo "'labels': " . json_encode($temperature_data['TIME']) . ",";
    echo "'temperature': " . json_encode($temperature_data['MESURE']) . ",";
    echo "'humidity': " . json_encode($humidity_data['MESURE']) . ",";
    echo "'co2': " . json_encode($co2_data['MESURE']);
    echo "};";
    echo "chart.data.labels = data.labels;";
    echo "chart.data.datasets[0].data = data.temperature;";
    echo "chart.data.datasets[1].data = data.humidity;";
    echo "chart.data.datasets[2].data = data.co2;";
    echo "chart.update();";
    echo "</script>";

} catch (PDOException $e) {
    // En cas d'erreur de connexion
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

</body>
</html>
