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
    require('../Includes/AccessDB.php');
    // Récupérer la dernière valeur de température
    $RecupTemperature = $bdd->query("SELECT MESURE FROM CAPTEUR WHERE ID_TYPE_CAPTEUR = 'T' ORDER BY ID_CAPTEUR DESC LIMIT 1")->fetchColumn();
    // Récupérer la dernière valeur d'humidité
    $RecupHumidity = $bdd->query("SELECT MESURE FROM CAPTEUR WHERE ID_TYPE_CAPTEUR = 'H' ORDER BY ID_CAPTEUR DESC LIMIT 1")->fetchColumn();
    // Récupérer la dernière valeur de CO2
    $RecupCO2 = $bdd->query("SELECT MESURE FROM CAPTEUR WHERE ID_TYPE_CAPTEUR = 'C' ORDER BY ID_CAPTEUR DESC LIMIT 1")->fetchColumn();

    $values =  $RecupTemperature . ',' . $RecupHumidity . ',' . $RecupCO2 . ',';

    echo $values;  
?>