<?php
// Index/Home page - Redirect to appropriate location
include 'includes/functions.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit();
?>
