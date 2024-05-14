<?php
    $conn = new PDO('sqlite:C:/xampp/htdocs/PCOScompanion/templates/databasepcos.db'); 
    if (!$conn) {
        die("Connection failed: " . $conn->errorInfo());
    }
?>

