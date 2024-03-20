<?php

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles/index.css">
</head>
<body>
    <div class="historique">
        <h2>Historique</h2>
        <p><u>Graphique de toutes les valeurs des capteurs</u></p>

        <div>
            <canvas id="temperatureChart" width="400" height="200"></canvas>
        </div>
        <div>
            <canvas id="humidityChart" width="400" height="200"></canvas>
        </div>
        <div>
            <canvas id="co2Chart" width="400" height="200"></canvas>
        </div>
    </div>
</body>
</html>