<?php
session_start();

// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if(!isset($_SESSION["LOGIN"])){
    header("Location: login");
    exit(); 
}

require('src/includes/accessDB.php');

// Connexion à la base de données
try {
    $bdd = new PDO("mysql:host=$host;dbname=$DB_Drac;charset=utf8mb4", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer l'état de l'utilisateur actuel
$LOGIN = $_SESSION["LOGIN"];
$ID_TYPE_USER = $bdd->prepare('SELECT ID_TYPE_USER FROM USER WHERE LOGIN = ?');
$ID_TYPE_USER->execute([$LOGIN]);
$result = $ID_TYPE_USER->fetch();

// Si l'utilisateur actuel a un état d'utilisateur égal à 1, rediriger vers la page d'accueil
if ($result['ID_TYPE_USER'] != 'A') {
    header("Location: .");
    exit();
}

// Traitement du formulaire d'enregistrement
if(isset($_POST['Register'])){
    if(!empty($_POST['ID_TYPE_USER']) && !empty($_POST['NAME']) && !empty($_POST['LASTNAME']) && !empty($_POST['PASSWORD'])){
        $ID_TYPE_USER = htmlspecialchars($_POST['ID_TYPE_USER']);
        $LOGIN = strtolower($_POST['NAME'] . '.' . $_POST['LASTNAME']);
        $PASSWORD = $_POST['PASSWORD'];
        $hashed_password = password_hash($PASSWORD, PASSWORD_DEFAULT);
        $NAME = htmlspecialchars($_POST['NAME']);
        $LASTNAME = htmlspecialchars($_POST['LASTNAME']);

        // Vérifier si l'utilisateur existe déjà
        $checkname = $bdd->prepare("SELECT LOGIN FROM USER WHERE LOGIN=:login");
        $checkname->execute([':login' => $LOGIN]);
        $rowCount = $checkname->rowCount();
        if ($rowCount == 0) {
            // Insérer un nouvel utilisateur
            $InsertUser = $bdd->prepare('INSERT INTO USER (ID_TYPE_USER, LOGIN, PASSWORD, NAME, LASTNAME, ETAT_USER) VALUES (?, ?, ?, ?, ?, ?)');
            $InsertUser->execute([$ID_TYPE_USER, $LOGIN, $hashed_password, $NAME, $LASTNAME, '1']);

            // Vérifier si l'insertion a réussi
            if($InsertUser->rowCount() > 0){
                echo "<script type='text/javascript'>alert('L'utilisateur a été créé avec succès.');</script>";
            } else {
                echo "<script type='text/javascript'>alert('Une erreur s'est produite lors de la création de l'utilisateur.');</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('L'utilisateur existe déjà.');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Veuillez remplir tous les champs.');</script>";
    }
}

// Bouton déconnexion
if(isset($_POST['logout'])){
    session_destroy();
    header('location: login');
}
?>

<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles/manage.css">
    <title>Création d'utilisateur</title>
</head>
<body>
    <div class="box">
        <form method="POST">
            <h2>Créer un utilisateur</h2>
            <select name="ID_TYPE_USER" id="ID_TYPE_USER" required>
                <option value="" disabled selected>-- Veuillez choisir une option --</option>
                <option value="A">Admin</option>
                <option value="I">Ingénieur</option>
                <option value="T">Technicien</option>
            </select>
            <input type="text" name="NAME" placeholder="Prénom" required>
            <input type="text" name="LASTNAME" placeholder="Nom" required>
            <input type="password" name="PASSWORD" placeholder="Mot de passe" required>
            <input type="submit" name="Register" value="Enregistrer">
        </form>
    </div>
    <div class="footer">
        <form method='POST'>
            <button type='submit' name='logout' class='btnLogout'>Déconnexion<i class='fa-solid fa-right-from-bracket'></i></button>
        </form>
        <p>Par Quentin DELOS & Redwan AZARFANE</p>
    </div>
</body>
</html>
