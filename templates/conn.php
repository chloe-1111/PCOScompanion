<?php
    $conn = new PDO('sqlite:C:/xampp/htdocs/PCOS/templates/databasepcos.db'); 
    if (!$conn) {
        die("Connection failed: " . $conn->errorInfo());
    }
?>

