<?php
//TRUNCATE TABLE `interfaceihm`.`auth`;
// INSERT INTO `interfaceihm`.`auth` (name, password) VALUES ('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');

session_start();
if(isset($_SESSION["name"])){
    header("Location: .");
exit(); 
}

if(!isset($_SESSION["name"])){
    require ('src/includes/accessDB.php');

    //PDO
    $dsn = "mysql:host=$host;dbname=$dbIHM;charset=utf8mb4";

    //DB -> PDO
    try {
        $bdd = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    //Login
    if(isset($_POST['submit_login'])){
        if(!empty($_POST['name']) && !empty($_POST['password'])){
            $name = htmlspecialchars($_POST['name']);
            $password = sha1($_POST['password']);
        
            $recupUser = $bdd->prepare('SELECT * FROM auth WHERE name = :name AND password = :password');
            $recupUser->execute(array(':name' => $name, ':password' => $password));

            $user = $recupUser->fetch();
            if($user){
                $_SESSION['name'] = $user['name'];                    
                $_SESSION['password'] = $password;
                $_SESSION['id_membre'] = $user['id_membre'];
                header('Location: .'); 
            } else {
                echo "<script type='text/javascript'>alert('Les identifiants que tu as mis ne sont pas corrects.');</script>";
            }
        }
    }

    //Register
    if(isset($_POST['submit_register'])){
        if(!empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])){
            $name = htmlspecialchars($_POST['name']);
            $checkname = $bdd->query("SELECT * FROM auth WHERE name='$name'");
            $result = $checkname->fetch();
                if (!$result) {
                    if ($_POST["password"] === $_POST["confirm_password"]){
                        $password = sha1($_POST['password']);
                        $insertUser = $bdd->prepare('INSERT INTO auth(name, password)VALUES(?, ?)');
                        $insertUser->execute(array($name, $password));
    
                        $recupUser = $bdd->prepare('SELECT * FROM auth WHERE name = ? AND password = ?');
                        $recupUser->execute(array($name, $password));
    
                            if($recupUser->rowCount() > 0){
                                $_SESSION['name'] = $name;
                                $_SESSION['password'] = $password;
                                $_SESSION['id_membre'] = $recupUser->fetch()['id_membre'];
                                header('Location: .');
                            }
                        } else {
                            echo "<script type='text/javascript'>alert('Les mots de passe ne correspondent pas.');</script>";
                        }
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
            <h1>Login</h1>
            <input type="text" name="name" placeholder="login" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="submit" name="submit_login" value="Login">
        </form>
        <!-- <form method="POST">
            <h2>Inscription</h2>
            <input type="text" name="name" placeholder="Pseudo" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>
            <input type="submit" name="submit_register" value="Register">
        </form> -->
    </div>
</body>
</html>
