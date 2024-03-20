<?php
require('accessDB.php');

// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué: " . $conn->connect_error);
}

// Fonction pour récupérer les données d'un capteur
function getDataFromSensor($conn, $tableName) {
    $data = array();
    $labels = array();

    $sql = "SELECT * FROM $tableName ORDER BY id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['timestamp']; // Remplacer 'timestamp' par le nom de votre colonne de date
            $data[] = $row['value'];
        }
    }

    return array('labels' => $labels, 'data' => $data);
}

// Récupérer les données de chaque capteur
$temperatureData = getDataFromSensor($conn, 'loratabletemperature');
$humidityData = getDataFromSensor($conn, 'loratablehumidity');
$co2Data = getDataFromSensor($conn, 'loratableco2');

// Renvoyer les données au format JSON
echo json_encode(array('temperature' => $temperatureData, 'humidity' => $humidityData, 'co2' => $co2Data));

// Fermer la connexion à la base de données
$conn->close();
?>