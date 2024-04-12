<?php
session_start();

// Si l'agent n'est pas connecté
if(!isset($_SESSION["LOGIN"])){
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
    <script src="src/scripts/index.js"></script>
    <title>Surveillance des serveurs</title>
</head>
<body>
    <?php include 'src/includes/nav-bar.php'; ?> 
    <?php include 'src/includes/data.html'; ?>
</body>
</html>