<?php
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers la page principale
if(isset($_SESSION["LOGIN"])){
    header("Location: .");
    exit(); 
}

if(!isset($_SESSION["LOGIN"])){
    // Connexion à la base de données
    require ('src/Includes/AccessDB.php');
    try {
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Login
    if(isset($_POST['submit'])){
        if(!empty($_POST['LOGIN']) && !empty($_POST['PASSWORD'])){
            $LOGIN = htmlspecialchars($_POST['LOGIN']);
            $PASSWORD = $_POST['PASSWORD'];
        
            $recupUser = $bdd->prepare("SELECT * FROM USER WHERE LOGIN = :LOGIN");
            $recupUser->execute(array(':LOGIN' => $LOGIN));
            $user = $recupUser->fetch();

            if (password_verify($PASSWORD, $user['PASSWORD'])) {
                $_SESSION['LOGIN'] = $user['LOGIN'];
                $_SESSION['FIRSTNAME'] = $user['FIRSTNAME'];
                $_SESSION['LASTNAME'] = $user['LASTNAME'];
                $_SESSION['ID_TYPE_USER'] = $user['ID_TYPE_USER'];
                header('Location: .');
            } else {
                echo "<script type='text/javascript'>alert('Mot de passe incorrect.');</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="src/Styles/Login.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Authentification</title>
</head>
<body>
    <?php include 'src/Templates/Login.html'; ?> 
</body>
</html>