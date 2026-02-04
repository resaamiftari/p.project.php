<?php
// Admin Password Reset Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Admin Password Reset</h1>";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "lmms";

$conn = @new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("<p style='color: red;'>Database connection failed: " . $conn->connect_error . "</p>");
}

// Check if admin user exists
$check = $conn->query("SELECT id, username FROM users WHERE username = 'admin'");

if ($check->num_rows > 0) {
    echo "<p style='color: green;'>✓ Admin user found in database</p>";
    
    // Reset password to 'admin123'
    $new_password = 'admin123';
    $hashed = password_hash($new_password, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("UPDATE users SET password = ?, confirm_password = ? WHERE username = 'admin'");
    $stmt->bind_param("ss", $hashed, $hashed);
    
    if ($stmt->execute()) {
        echo "<div style='background: #d4edda; padding: 20px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
        echo "<h2 style='color: #155724;'>✓ Password Reset Successful!</h2>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><a href='login.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>Failed to reset password: " . $stmt->error . "</p>";
    }
    $stmt->close();
    
} else {
    echo "<p style='color: orange;'>⚠ Admin user not found. Creating new admin user...</p>";
    
    // Create admin user
    $emri = "Admin User";
    $username_val = "admin";
    $email = "admin@library.com";
    $new_password = "admin123";
    $hashed = password_hash($new_password, PASSWORD_BCRYPT);
    $is_admin = 1;
    
    $stmt = $conn->prepare("INSERT INTO users (emri, username, email, password, confirm_password, is_admin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $emri, $username_val, $email, $hashed, $hashed, $is_admin);
    
    if ($stmt->execute()) {
        echo "<div style='background: #d4edda; padding: 20px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
        echo "<h2 style='color: #155724;'>✓ Admin User Created!</h2>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><a href='login.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>Failed to create admin user: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Show current hash for verification
echo "<hr>";
echo "<h3>Current Admin Password Hash:</h3>";
$result = $conn->query("SELECT password FROM users WHERE username = 'admin'");
if ($result && $row = $result->fetch_assoc()) {
    echo "<code>" . htmlspecialchars($row['password']) . "</code>";
}

$conn->close();
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
}
</style>
