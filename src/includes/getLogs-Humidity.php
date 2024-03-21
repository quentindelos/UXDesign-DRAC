<?php
    require ('accessDB.php');
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT value, time FROM loratablehumidity";
    $result = $conn->query($sql);
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    

    $conn->close();
?>