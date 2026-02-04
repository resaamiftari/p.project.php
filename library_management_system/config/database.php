<?php
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "lmms";

try {
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>
