<?php
require ('accessDB.php');
$conn = new mysqli($host, $username, $password, $dbLora);

// Récupérer la dernière valeur de température
$temperature = "SELECT value FROM loratabletemperature ORDER BY id DESC LIMIT 1";
$result = $conn->query($temperature);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $temperature_value = $row["value"];
} else {
    $temperature_value = "Aucune donnée";
}

// Récupérer la dernière valeur d'humidité
$humidity = "SELECT value FROM loratablehumidite ORDER BY id DESC LIMIT 1";
$result = $conn->query($humidity);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $humidity_value = $row["value"];
} else {
    $humidity_value = "Aucune donnée";
}

// Récupérer la dernière valeur de CO2
$co2 = "SELECT value FROM loratableCO2 ORDER BY id DESC LIMIT 1";
$result = $conn->query($co2);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $co2_value = $row["value"];
} else {
    $co2_value = "Aucune donnée";
}

$values = $temperature_value . ',' . $humidity_value . ',' . $co2_value;

echo $values;
$conn->close();
?>
