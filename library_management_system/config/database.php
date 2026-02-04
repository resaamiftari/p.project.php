<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "lmms";

// Suppress connection error to database that doesn't exist yet
@$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // If database doesn't exist, show friendly message
    if ($conn->connect_errno == 1049) {
        die("<div style='padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;'>
            <h3>Database Not Found</h3>
            <p>The database '<strong>lmms</strong>' does not exist yet.</p>
            <p><strong>Please follow these steps:</strong></p>
            <ol>
                <li>Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>
                <li>Create a new database named: <strong>lmms</strong></li>
                <li>Import the SQL file: <strong>config/schema.sql</strong></li>
                <li>Refresh this page</li>
            </ol>
            <p>See <a href='QUICKSTART.md'>QUICKSTART.md</a> for detailed instructions.</p>
        </div>");
    }
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8mb4");
?>
