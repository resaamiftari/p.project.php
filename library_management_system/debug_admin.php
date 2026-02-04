<?php
// Comprehensive Admin Login Debug & Fix Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Admin Login Troubleshooting & Fix</h1>";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "lmms";

echo "<h2>Step 1: Database Connection</h2>";
$conn = @new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>");
}
echo "<p style='color: green;'>✓ Connected to MySQL</p>";

// Check if database exists
$db_check = $conn->query("SELECT DATABASE()");
if ($db_check) {
    echo "<p style='color: green;'>✓ Database 'lmms' selected</p>";
} else {
    die("<p style='color: red;'>❌ Cannot select database 'lmms'</p>");
}

echo "<h2>Step 2: Check Users Table</h2>";
$table_check = $conn->query("SHOW TABLES LIKE 'users'");
if ($table_check->num_rows > 0) {
    echo "<p style='color: green;'>✓ Users table exists</p>";
} else {
    die("<p style='color: red;'>❌ Users table not found. Please import schema.sql</p>");
}

echo "<h2>Step 3: Check Admin User</h2>";
$admin_check = $conn->query("SELECT id, username, email, password, is_admin FROM users WHERE username = 'admin'");

if ($admin_check->num_rows > 0) {
    $admin = $admin_check->fetch_assoc();
    echo "<p style='color: green;'>✓ Admin user exists</p>";
    echo "<p>ID: " . $admin['id'] . "</p>";
    echo "<p>Username: " . $admin['username'] . "</p>";
    echo "<p>Email: " . $admin['email'] . "</p>";
    echo "<p>Is Admin: " . $admin['is_admin'] . "</p>";
    echo "<p>Password Hash: <code>" . htmlspecialchars($admin['password']) . "</code></p>";
    
    // Test password verification
    echo "<h2>Step 4: Test Password Verification</h2>";
    $test_password = 'admin123';
    $hash_match = password_verify($test_password, $admin['password']);
    
    if ($hash_match) {
        echo "<p style='color: green;'>✓ Password 'admin123' verifies correctly with stored hash</p>";
    } else {
        echo "<p style='color: red;'>❌ Password 'admin123' does NOT match the stored hash</p>";
        echo "<p>Attempting to fix...</p>";
        
        // Fix by resetting password
        $new_hash = password_hash($test_password, PASSWORD_BCRYPT);
        $fix_stmt = $conn->prepare("UPDATE users SET password = ?, confirm_password = ? WHERE username = 'admin'");
        $fix_stmt->bind_param("ss", $new_hash, $new_hash);
        
        if ($fix_stmt->execute()) {
            echo "<p style='color: green;'>✓ Password hash has been reset and fixed</p>";
            echo "<p>New Hash: <code>" . htmlspecialchars($new_hash) . "</code></p>";
            
            // Verify the fix
            $verify_new = password_verify($test_password, $new_hash);
            if ($verify_new) {
                echo "<p style='color: green;'>✓ New password verifies correctly!</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Failed to update password: " . $fix_stmt->error . "</p>";
        }
        $fix_stmt->close();
    }
    
} else {
    echo "<p style='color: orange;'>⚠ Admin user not found. Creating new admin...</p>";
    
    $emri = "Admin User";
    $username_val = "admin";
    $email_val = "admin@library.com";
    $test_password = 'admin123';
    $new_hash = password_hash($test_password, PASSWORD_BCRYPT);
    $is_admin_val = 1;
    
    $create_stmt = $conn->prepare("INSERT INTO users (emri, username, email, password, confirm_password, is_admin) VALUES (?, ?, ?, ?, ?, ?)");
    $create_stmt->bind_param("sssssi", $emri, $username_val, $email_val, $new_hash, $new_hash, $is_admin_val);
    
    if ($create_stmt->execute()) {
        echo "<p style='color: green;'>✓ Admin user created successfully</p>";
        echo "<p>Username: admin</p>";
        echo "<p>Password: admin123</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create admin: " . $create_stmt->error . "</p>";
    }
    $create_stmt->close();
}

echo "<h2>Step 5: Final Result</h2>";
echo "<div style='background: #d4edda; padding: 20px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
echo "<h3 style='color: #155724;'>✓ Admin Account Ready!</h3>";
echo "<p><strong>Username:</strong> admin</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p><a href='login.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Login Page →</a></p>";
echo "</div>";

echo "<h2>🔍 Troubleshooting Tips:</h2>";
echo "<ul>";
echo "<li>Make sure MySQL is running in XAMPP</li>";
echo "<li>Make sure you imported config/schema.sql into database 'lmms'</li>";
echo "<li>Try clearing your browser cache (Ctrl+Shift+Delete)</li>";
echo "<li>Make sure cookies are enabled in your browser</li>";
echo "</ul>";

$conn->close();
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background: #f5f5f5;
}
h1 { color: #2c3e50; }
h2 { color: #34495e; margin-top: 30px; }
code { background: #f4f4f4; padding: 5px 10px; border-radius: 3px; font-family: monospace; }
a { color: #3498db; }
</style>
