<?php
header("Content-type: application/json");
$json = file_get_contents("php://input");
$obj = json_decode($json);
$decoded = base64_decode(json_encode($obj->data));
$notdecode = json_encode($obj->data);
$combine = "$decode - $notdecode";
$binary = base64_decode($combine);
$hex = bin2hex($binary);

$humidite = hexdec($hex[8])*16 + hexdec($hex[9]);
$temp1 = hexdec($hex[4])*16 + hexdec($hex[5]);
$temp2 = hexdec($hex[6])*16 + hexdec($hex[7]);
$temperature = ( $temp1*256 + $temp2 )/10;
$C1 = hexdec($hex[10])*16 + hexdec($hex[11]);
$C2 = hexdec($hex[12])*16 + hexdec($hex[13]);
$CO2 = ($C1*256 + $C2);

$host = "localhost:3306";
$username = "root";
$password = "";
$DB = "bdd_drac";

try {
    $bdd = new PDO("mysql:host=$host;dbname=$DB;charset=utf8mb4", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $insertTemp =  $bdd->prepare('INSERT INTO CAPTEUR (ID_TYPE_CAPTEUR, MESURE) VALUES (?, ?)');
    $insertTemp->execute(['T', $temperature]);

    $insertHumidity =  $bdd->prepare('INSERT INTO CAPTEUR (ID_TYPE_CAPTEUR, MESURE) VALUES (?, ?)');
    $insertHumidity->execute(['H', $humidite]);

    $insertCO2 = $bdd->prepare('INSERT INTO CAPTEUR (ID_TYPE_CAPTEUR, MESURE) VALUES (?, ?)');
    $insertCO2->execute(['C', $CO2]);

    echo json_encode(array("message" => "Data inserted successfully."));
} catch(PDOException $e) {
    echo json_encode(array("message" => "Database error: " . $e->getMessage()));
}


require ("DashboardSensorDRAC/src/Includes/PHPMailer-Files/script.php");
$EmailLists = $bdd->prepare("SELECT EMAIL FROM USER WHERE USER.ID_TYPE_USER = 'T'");
$EmailLists->execute();

$emails = array();
while ($row = $EmailLists->fetch(PDO::FETCH_ASSOC)) {
    $emails[] = $row['EMAIL'];
}

$subject = "Alertes Capteurs";
if ($temperature > 30) {
    if ($temperature > 32) {
        $message = "La température est de " . $temperature . "°C, elle est critique.<br>Veuillez intervenir le plus vite possible.";
        foreach ($emails as $email) {
            sendMail($email, $subject, $message);
        }
    }   
    $message = "La température est de " . $temperature . "°C, elle est au dela de la moyenne.<br>Veuillez intervenir.";
    foreach ($emails as $email) {
        sendMail($email, $subject, $message);
    }
}


if ($humidite > 75) {
    if ($humidite > 90) {
        $message = "L'humidité est de " . $humidite . "%, il est critique.<br>Veuillez intervenir le plus vite possible.";
        foreach ($emails as $email) {
            sendMail($email, $subject, $message);
        }
    }   
    $message = "L'humidité est de " . $humidite . "%, il est au dela de la moyenne.<br>Veuillez intervenir.";
    foreach ($emails as $email) {
        sendMail($email, $subject, $message);
    }
}


if ($CO2 > 700) {
    if ($CO2 > 800) {
        $message = "Le CO2 est de " . $CO2 . "PPM, il est critique.<br>Veuillez intervenir le plus vite possible.";
        foreach ($emails as $email) {
            sendMail($email, $subject, $message);
        }
    }   
    $message = "Le CO2 est de " . $CO2 . "PPM, il est au dela de la moyenne.<br>Veuillez intervenir.";
    foreach ($emails as $email) {
        sendMail($email, $subject, $message);
    }
}
$bdd = null;
?>