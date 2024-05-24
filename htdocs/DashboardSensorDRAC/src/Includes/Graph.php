<?php
session_start();
// Si l'agent n'est pas connecté
if(!isset($_SESSION["LOGIN"])){
    header("Location: ../../Login");
    exit(); 
}

// Bouton déconnexion
if(isset($_POST['logout'])){
    session_destroy();
    header('location: ../../Login');
}

// Connexion à la base de données
require('AccessDB.php');

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$data = array('labels' => [], 'temperature' => [], 'humidity' => [], 'co2' => []);

try {
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Modification des requêtes SQL pour sélectionner les données du jour sélectionné
    $sql_temperature = "SELECT DATE_FORMAT(TIME, '%H:%i') AS TIME, MESURE FROM `CAPTEUR` WHERE ID_TYPE_CAPTEUR = 'T' AND DATE(TIME) = :date ORDER BY ID_CAPTEUR";
    $stmt_temperature = $bdd->prepare($sql_temperature);
    $stmt_temperature->execute(['date' => $date]);

    $sql_humidity = "SELECT DATE_FORMAT(TIME, '%H:%i') AS TIME, MESURE FROM `CAPTEUR` WHERE ID_TYPE_CAPTEUR = 'H' AND DATE(TIME) = :date ORDER BY ID_CAPTEUR";
    $stmt_humidity = $bdd->prepare($sql_humidity);
    $stmt_humidity->execute(['date' => $date]);

    $sql_co2 = "SELECT DATE_FORMAT(TIME, '%H:%i') AS TIME, MESURE FROM `CAPTEUR` WHERE ID_TYPE_CAPTEUR = 'C' AND DATE(TIME) = :date ORDER BY ID_CAPTEUR";
    $stmt_co2 = $bdd->prepare($sql_co2);
    $stmt_co2->execute(['date' => $date]);

    while ($row = $stmt_temperature->fetch(PDO::FETCH_ASSOC)) {
        $data['labels'][] = $row['TIME'];
        $data['temperature'][] = $row['MESURE'];
    }

    while ($row = $stmt_humidity->fetch(PDO::FETCH_ASSOC)) {
        $data['humidity'][] = $row['MESURE'];
    }

    while ($row = $stmt_co2->fetch(PDO::FETCH_ASSOC)) {
        $data['co2'][] = $row['MESURE'];
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique en temps réel</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var ctx = document.getElementById('graphique').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($data['labels']); ?>,
                    datasets: [{
                        label: 'Température',
                        data: <?php echo json_encode($data['temperature']); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 3,
                        lineTension: 0.5,
                    }, {
                        label: 'Humidité',
                        data: <?php echo json_encode($data['humidity']); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 3,
                        lineTension: 0.5,
                    }, {
                        label: 'CO2',
                        data: <?php echo json_encode($data['co2']); ?>,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 3,
                        lineTension: 0.5,
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

            document.getElementById('datePicker').addEventListener('change', function() {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var data = JSON.parse(xhr.responseText);
                            chart.data.labels = data.labels;
                            chart.data.datasets[0].data = data.temperature;
                            chart.data.datasets[1].data = data.humidity;
                            chart.data.datasets[2].data = data.co2;
                            chart.update();
                        }
                    }
                };
                xhr.open("GET", "index.php?date=" + this.value, true);
                xhr.send();
            });

            setInterval(() => {
                const date = document.getElementById('datePicker').value;
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var data = JSON.parse(xhr.responseText);
                            chart.data.labels = data.labels;
                            chart.data.datasets[0].data = data.temperature;
                            chart.data.datasets[1].data = data.humidity;
                            chart.data.datasets[2].data = data.co2;
                            chart.update();
                        }
                    }
                };
                xhr.open("GET", "index.php?date=" + date, true);
                xhr.send();
            }, 2000);
        });
    </script>
</head>
<body>
    <?php include '../Templates/Header.php'; ?>
    
    <form>
        <label for="datePicker">Sélectionnez une date :</label>
        <input type="date" id="datePicker" name="date" value="<?php echo date('Y-m-d'); ?>">
    </form>

    <div class="historique">
        <canvas id="graphique"></canvas>
    </div>
</body>
</html>
