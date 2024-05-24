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
?>

<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/DashboardSensorDRAC/src/Styles/Dashboard.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="/DashboardSensorDRAC/src/Scripts/sensors.js"></script>
   
</head>
<body>
    <?php include '../Templates/Header.php'; ?>
<div class="gauge-container">
    <div class="center-content">
        <div class="gauge-div">
            <div id="temp_div"></div>
            <div id="temperature" class="sensor-value">Température actuelle : </div>
        </div>
        <div class="gauge-div">
            <div id="hum_div"></div>
            <div id="humidity" class="sensor-value">Taux d'humidité actuel : </div>
        </div>
        <div class="gauge-div">
            <div id="co2_div"></div>
            <div id="CO2" class="sensor-value">CO2 actuel : </div>
        </div>
        <div class="error-message" id="error-message"></div>
    </div>
</div>


    </main>
</body>
</html>
