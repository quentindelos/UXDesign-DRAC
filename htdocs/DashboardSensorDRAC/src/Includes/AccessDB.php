<?php
$host = "localhost";
$username = "root";
$password = "";
$DB = "bdd_drac";

$bdd = new PDO("mysql:host=$host;dbname=$DB;charset=utf8mb4", $username, $password);