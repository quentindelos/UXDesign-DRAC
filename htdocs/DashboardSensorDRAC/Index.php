<?php
session_start();

// Regenerate session ID to prevent session fixation
session_regenerate_id(true);

// Vérification de la session utilisateur
if (!isset($_SESSION["LOGIN"])) {
    header("Location: Login");
    exit();
}

// Déconnexion de l'utilisateur
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: Login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet DRAC AKCP</title>
    <link rel="stylesheet" href="/DashboardSensorDRAC/src/Styles/home.css">
</head>
<body>
    <header>
        <?php include 'src/Templates/Header.php'; ?> <!-- Inclusion du header -->
    </header>

    <div class="contain">
        <h2>Bienvenue sur le projet DRAC AKCP</h2>
        <p>En collaboration avec la Direction Régionale des Affaires Culturelles, ce projet vise à sécuriser et optimiser les infrastructures informatiques critiques suite à un incident majeur au sein des datacenters du bâtiment.</p>

        <h2>Explication</h2>
        <p>Les serveurs de la DRAC ont dû être sécurisés, entraînant l'interruption de nombreux services essentiels. Cet événement a souligné l'importance d'une surveillance continue de la température et de l'humidité dans les datacenters.</p>

        <h3>Résolution</h3>
        <p>Pour résoudre ces problèmes, une installation de capteurs de température et d'humidité a été mise en place pour assurer la stabilité et la sécurité des infrastructures. Ce projet vise à prévenir de futurs incidents et à maintenir une disponibilité élevée des services culturels critiques.</p>

        <!-- Formulaire de déconnexion -->
        <form method="post" action="">
            <button type="submit" name="logout" class="logout-button">Déconnexion</button>
        </form>
    </div>
</body>
</html>
