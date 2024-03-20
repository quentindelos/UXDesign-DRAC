<?php
session_start();

// Si l'agent n'est pas connecté
if(!isset($_SESSION["name"])){
    header("Location: login");
exit(); 
}

// Bouton déconnexion
if(isset($_POST['logout'])){
    session_destroy();
    header('location: login');
}
?>

<!DOCTYPE html>
<html lang="FR_fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles/index.css">
    <title>Surveillance des serveurs</title>
</head>
<body>
<!-- Pages -->
    <?php include 'src/includes/nav-bar.php'; ?>
    <?php include 'src/includes/historique.php'; ?>
    <?php include 'src/includes/sensors.php'; ?>

<!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="src/scripts/index.js"></script>
</body>
</html>