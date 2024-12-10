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

    // Connexion à la base de données
    require('AccessDB.php');
    try {
        $bdd = new PDO("mysql:host=$host;dbname=$DB;charset=utf8mb4", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Changement de mot de passe
    if(isset($_POST['ChangePassword'])){
        if(!empty($_POST['OldPassword']) && !empty($_POST['NewPassword'])){
            $OldPassword = $_POST['OldPassword'];
            $NewPassword = $_POST['NewPassword'];
            $Hash_New_Password = password_hash($NewPassword, PASSWORD_DEFAULT);

            $recupUser = $bdd->prepare("SELECT * FROM USER WHERE LOGIN = :LOGIN");
            $recupUser->execute(array(':LOGIN' => $_SESSION["LOGIN"]));
            $user = $recupUser->fetch();

            if (password_verify($OldPassword, $user['PASSWORD'])) {
                $EditPassword = $bdd->prepare('UPDATE USER SET PASSWORD = ? WHERE LOGIN = ?');
                $EditPassword->execute([$Hash_New_Password, $_SESSION["LOGIN"]]);

                // Send password change confirmation email
                require ('PHPMailer-Files/script.php');
                $subject = "Modification du mot de passe";
                $message = "Bonjour " . $user['FIRSTNAME'] . ",<br>Votre mot de passe a correctement été modifié.<br><br>Bonne journée.";
                sendMail($user["EMAIL"], $subject, $message);
            } else {
                echo "<script type='text/javascript'>alert('Mot de passe incorrect.');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <?php include '../Templates/Header.php'; ?>
    <?php include '../Templates/Change_Password.html'; ?>
</body>
</html>