<?php
session_start();
    //Génère un mot de passe
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $length = strlen($alphabet);
    $TempPassword = "";
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $length - 1);
        $TempPassword .= $alphabet[$n];
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
    if (isset($_POST['SendNewPassword'])) {
        if (!empty($_POST['EMAIL'])) {
            $EMAIL = filter_var($_POST['EMAIL'], FILTER_SANITIZE_EMAIL);

            if (filter_var($EMAIL, FILTER_VALIDATE_EMAIL)) {
                $NewPassword = password_hash($TempPassword, PASSWORD_DEFAULT);

                $EditPassword = $bdd->prepare('UPDATE USER SET PASSWORD = ? WHERE EMAIL = ?');
                if ($EditPassword->execute([$NewPassword, $EMAIL])) {
                    require('PHPMailer-Files/script.php');
                    $subject = "Changement de votre mot de passe";
                    $message = "Bonjour,<br>Suite à votre demande de nouveau mot de passe voici votre mot de passe temporaire : " . $TempPassword . "<br>Pensez à le modifier dans les paramètres de votre profil.<br><br>Bonne journée.";
                    sendMail($EMAIL, $subject, $message);
                    echo "Un nouveau mot de passe a été envoyé à votre adresse e-mail.";
                } else {
                    echo "Erreur lors de la mise à jour du mot de passe.";
                }
            } else {
                echo "Adresse e-mail invalide.";
            }
        } else {
            echo "Veuillez saisir une adresse e-mail.";
        }
    }
?>


<!DOCTYPE html>
<html lang="fr_FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
</head>
<body>
    <?php include '../Templates/Forgot_Password.html'; ?>
</body>
</html>