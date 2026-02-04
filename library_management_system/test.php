<?php
// Simple test page to verify PHP is working
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PHP Test Page</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Current Time: " . date('Y-m-d H:i:s') . "</p>";

// Test database connection
echo "<h2>Database Connection Test</h2>";
$servername = "localhost";
$username = "root";
$password = "";
$database = "lmms";

$conn = @new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    echo "<p style='color: red;'>MySQL Connection Failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✓ MySQL Connection Successful</p>";
    
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE 'lmms'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Database 'lmms' exists</p>";
        
        // Select database and check tables
        $conn->select_db($database);
        $tables = ['users', 'books', 'reservations'];
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>✓ Table '$table' exists</p>";
            } else {
                echo "<p style='color: red;'>✗ Table '$table' not found</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>✗ Database 'lmms' not found - Please create it and import schema.sql</p>";
    }
}

// Test file includes
echo "<h2>File Structure Test</h2>";
$files = [
    'includes/functions.php',
    'includes/header.php',
    'includes/navbar.php',
    'includes/footer.php',
    'config/database.php',
    'assets/css/style.css'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✓ $file exists</p>";
    } else {
        echo "<p style='color: red;'>✗ $file not found</p>";
    }
}

echo "<hr>";
echo "<p><a href='login.php'>Go to Login Page</a></p>";
echo "<p><a href='setup.php'>Go to Setup Page</a></p>";
?>
