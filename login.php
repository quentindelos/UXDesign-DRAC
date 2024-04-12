<?php
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers la page principale
if(isset($_SESSION["LOGIN"])){
    header("Location: .");
    exit(); 
}

if(!isset($_SESSION["LOGIN"])){
    require ('src/includes/accessDB.php');

    $dsn = "mysql:host=$host;dbname=$DB_Drac;charset=utf8mb4";
    try {
        $bdd = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    // Login
    if(isset($_POST['submit'])){
        if(!empty($_POST['LOGIN']) && !empty($_POST['PASSWORD'])){
            $LOGIN = htmlspecialchars($_POST['LOGIN']);
            $PASSWORD = $_POST['PASSWORD'];
        
            $recupUser = $bdd->prepare('SELECT * FROM USER WHERE LOGIN = :LOGIN');
            $recupUser->execute(array(':LOGIN' => $LOGIN));
            $user = $recupUser->fetch();

            if($user && password_verify($PASSWORD, $user['PASSWORD'])){
                $_SESSION['LOGIN'] = $user['LOGIN'];                    
                header('Location: .');
                exit();
            } else {
                echo "<script type='text/javascript'>alert('Les identifiants que tu as saisis ne sont pas corrects.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles/auth.css">
    <title>Authentification</title>
</head>
<body>
    <div class="box">
        <form method="POST">
            <label>Identifiant (prénom.nom) :</label>
            <br>
            <input type="text" name="LOGIN">
            <br>
            <label>Mot de passe:</label>
            <br>
            <input type="password" name="PASSWORD">
            <br><br>
            <input type="submit" name="submit" value="Se connecter">
        </form>
    </div>
</body>
</html>
