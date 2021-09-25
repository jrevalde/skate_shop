<?php
    $conn = new mysqli("localhost", "root", "",  "skate_shop");
    if ($conn->connect_error)
    {
        die("Connection Failed" . $conn->connect_error);
    }

    
?>