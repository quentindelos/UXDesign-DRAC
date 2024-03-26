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
            xhr.open('GET', true);
            xhr.send();
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
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <?php
        require ('accessDB.php');
        $conn = new mysqli($servername, $username, $password, $dbLora);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Récupérer les données de la table loratabletemperature
        $sql_temperature = "SELECT * FROM loratabletemperature";
        $result_temperature = $conn->query($sql_temperature);
        $temperature_data = array('time' => array(), 'value' => array());

        // Récupérer les données de la table loratablehumidite
        $sql_humidity = "SELECT * FROM loratablehumidite";
        $result_humidity = $conn->query($sql_humidity);
        $humidity_data = array('time' => array(), 'value' => array());

        // Récupérer les données de la table loratableco2
        $sql_co2 = "SELECT * FROM loratableCO2";
        $result_co2 = $conn->query($sql_co2);
        $co2_data = array('time' => array(), 'value' => array());

        // Parcourir les résultats et les ajouter aux tableaux de données respectifs
        function fetch_data($result, &$data) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($data['time'], $row['time']);
                    array_push($data['value'], $row['value']);
                }
            }
        }

        fetch_data($result_temperature, $temperature_data);
        fetch_data($result_humidity, $humidity_data);
        fetch_data($result_co2, $co2_data);

        // Fermer la connexion à la base de données
        $conn->close();

        // Renvoyer les données au format JSON
        echo "<script>";
        echo "var data = {";
        echo "'labels': " . json_encode($temperature_data['time']) . ",";
        echo "'temperature': " . json_encode($temperature_data['value']) . ",";
        echo "'humidity': " . json_encode($humidity_data['value']) . ",";
        echo "'co2': " . json_encode($co2_data['value']);
        echo "};";
        echo "chart.data.labels = data.labels;";
        echo "chart.data.datasets[0].data = data.temperature;";
        echo "chart.data.datasets[1].data = data.humidity;";
        echo "chart.data.datasets[2].data = data.co2;";
        echo "chart.update();";
        echo "</script>";
    ?>

</body>
</html>