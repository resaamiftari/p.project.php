<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .setup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        .setup-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        .setup-body {
            padding: 30px;
        }
        .setup-step {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            border-radius: 5px;
        }
        .step-number {
            display: inline-block;
            background: #3498db;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            margin-right: 10px;
        }
        .success {
            color: #27ae60;
        }
        .error {
            color: #e74c3c;
        }
        .warning {
            color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="setup-header">
            <h1><i class="fas fa-cog"></i> Setup Wizard</h1>
            <p>Library Management System v1.0</p>
        </div>
        <div class="setup-body">
            <?php
            $errors = [];
            $warnings = [];
            $success_items = [];

            // Check PHP Version
            if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
                $success_items[] = "PHP Version: " . PHP_VERSION . " ✓";
            } else {
                $errors[] = "PHP version 7.0 or higher is required (Current: " . PHP_VERSION . ")";
            }

            // Check MySQL Extension
            if (extension_loaded('mysqli')) {
                $success_items[] = "MySQLi Extension: Installed ✓";
            } else {
                $errors[] = "MySQLi extension is not installed";
            }

            // Check GD Extension
            if (extension_loaded('gd')) {
                $success_items[] = "GD Library: Installed ✓";
            } else {
                $warnings[] = "GD Library not installed (needed for image generation)";
            }

            // Check File Permissions
            $dirs_to_check = [
                'assets/images/books',
                'config'
            ];

            foreach ($dirs_to_check as $dir) {
                if (is_writable($dir)) {
                    $success_items[] = "Directory writable: $dir ✓";
                } else {
                    if (!is_dir($dir)) {
                        $warnings[] = "Directory not found: $dir";
                    } else {
                        $errors[] = "Directory not writable: $dir (Need 755 or 777 permissions)";
                    }
                }
            }

            // Check Database Connection
            $db_config = [
                'host' => 'localhost',
                'user' => 'root',
                'pass' => '',
                'db' => 'lmms'
            ];

            @$conn = mysqli_connect($db_config['host'], $db_config['user'], $db_config['pass']);
            
            if ($conn) {
                $success_items[] = "MySQL Server: Connected ✓";
                
                // Check if database exists
                if (mysqli_select_db($conn, $db_config['db'])) {
                    $success_items[] = "Database 'lmms': Exists ✓";
                    
                    // Check tables
                    $tables = ['users', 'books', 'reservations'];
                    foreach ($tables as $table) {
                        $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
                        if (mysqli_num_rows($result) > 0) {
                            $success_items[] = "Table '$table': Exists ✓";
                        } else {
                            $errors[] = "Table '$table' not found in database";
                        }
                    }
                } else {
                    $warnings[] = "Database 'lmms' not found (needs to be created)";
                }
                mysqli_close($conn);
            } else {
                $errors[] = "Cannot connect to MySQL server on 'localhost' as 'root' with no password";
            }

            // Check Files
            $files_to_check = [
                'config/database.php',
                'includes/functions.php',
                'assets/css/style.css'
            ];

            foreach ($files_to_check as $file) {
                if (file_exists($file)) {
                    $success_items[] = "File exists: $file ✓";
                } else {
                    $errors[] = "File not found: $file";
                }
            }
            ?>

            <h4 class="mb-3">System Check Results</h4>

            <?php if (!empty($success_items)): ?>
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle"></i> System Ready</h6>
                    <?php foreach ($success_items as $item): ?>
                        <div class="setup-step">
                            <span class="success"><i class="fas fa-check"></i> <?php echo $item; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($warnings)): ?>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> Warnings</h6>
                    <?php foreach ($warnings as $item): ?>
                        <div class="setup-step">
                            <span class="warning"><i class="fas fa-exclamation"></i> <?php echo $item; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <h6><i class="fas fa-times-circle"></i> Errors</h6>
                    <?php foreach ($errors as $item): ?>
                        <div class="setup-step">
                            <span class="error"><i class="fas fa-times"></i> <?php echo $item; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <hr>

            <h4 class="mb-3">Setup Instructions</h4>

            <div class="setup-step">
                <span class="step-number">1</span>
                <strong>Create Database</strong>
                <p class="mb-0 mt-2">Open phpMyAdmin and execute the SQL from <code>config/schema.sql</code> or run:<br>
                <code>mysql -u root -p < config/schema.sql</code></p>
            </div>

            <div class="setup-step">
                <span class="step-number">2</span>
                <strong>Generate Book Images</strong>
                <p class="mb-0 mt-2">Visit <a href="generate_images.php" target="_blank">generate_images.php</a> to create placeholder book cover images</p>
            </div>

            <div class="setup-step">
                <span class="step-number">3</span>
                <strong>Start Using</strong>
                <p class="mb-0 mt-2">Go to <a href="index.php">Home Page</a> or <a href="login.php">Login Page</a></p>
            </div>

            <div class="mt-4">
                <a href="login.php" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-sign-in-alt"></i> Go to Login
                </a>
            </div>

            <div class="mt-2 text-center text-muted">
                <small><a href="README.md" target="_blank">View Documentation</a></small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
