<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "mreza";

    $conn = new mysqli($servername, $username, $password, $database);
    if($conn->connect_error) {
        die("Connection error " . $conn->connect_error);
    }
    else {
    }