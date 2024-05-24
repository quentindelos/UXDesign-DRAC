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
        exit();
    }

    // Connexion à la base de données
    require('AccessDB.php');
    try {
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Récupérer les utilisateurs
    $recupUser = $bdd->prepare("SELECT FIRSTNAME, LASTNAME, EMAIL, LIBELLE_TYPE_USER FROM USER INNER JOIN TYPE_USER ON USER.ID_TYPE_USER = TYPE_USER.ID_TYPE_USER");
    $recupUser->execute();
    $users = $recupUser->fetchAll(PDO::FETCH_ASSOC);

    // Suppression de l'utilisateur si demandé
    if (isset($_POST['delete_user'])) {
        $userIdToDelete = $_POST['user_id'];
        $deleteUser = $bdd->prepare("DELETE FROM USER WHERE ID = :ID");
        $deleteUser->execute(array(':id' => $userIdToDelete));
        $deleteUser->execute();
    }
?>

<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
</head>
<body>
    <?php include '../Templates/Header.php'; ?>
    <?php include '../Templates/TemplatesUsers.php'; ?>
</body>
</html>