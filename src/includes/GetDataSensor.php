<?php

require('accessDB.php');

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$DB_Drac", $username, $password);

    // Récupérer la dernière valeur de température
    $RecupTemperature = $pdo->query("SELECT MESURE FROM CAPTEUR WHERE ID_TYPE_CAPTEUR = 'T' ORDER BY ID_CAPTEUR DESC LIMIT 1")->fetchColumn();
    // Récupérer la dernière valeur d'humidité
    $RecupHumidity = $pdo->query("SELECT MESURE FROM CAPTEUR WHERE ID_TYPE_CAPTEUR = 'H' ORDER BY ID_CAPTEUR DESC LIMIT 1")->fetchColumn();
    // Récupérer la dernière valeur de CO2
    $RecupCO2 = $pdo->query("SELECT MESURE FROM CAPTEUR WHERE ID_TYPE_CAPTEUR = 'C' ORDER BY ID_CAPTEUR DESC LIMIT 1")->fetchColumn();

    $values =  $RecupTemperature . ',' . $RecupHumidity . ',' . $RecupCO2;

    echo $values;

    // Fermer la connexion à la base de données
    $pdo = null;
} catch (PDOException $e) {
    // En cas d'erreur de connexion
    echo "Erreur de connexion : " . $e->getMessage();
}


?>


<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/sensors.js"></script>
    <script src="../styles/index.css"></script>
</head>
<body>
    <div class="sensors">
        <h2>Capteurs</h1>
        <p><u>Affiche toutes les valeurs des capteurs</u></p>
        <br>
        <p id="temperature"></p>
        <p id="humidity"></p>
        <p id="CO2"></p>
    </div>
</body>
</html> -->