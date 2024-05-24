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
    <title>Profile</title>
</head>
<body>
    <?php include '../Templates/Header.php'; ?>
    <main>
        <section>
            <H1>Paramètres de profil</H1>
                <?php
                    echo 'Bonjour ' . $_SESSION["FIRSTNAME"] . ' ' . $_SESSION["LASTNAME"];
                ?>
            <br><br>
            <a href="Change_Password">Changer mon mot de passe</a>
            <br>
            <a href="Recovery_Password">Mot de passe oublié</a>
        </section>
    </main>
    </body>
</html>