<?php
// Database Configuration for XAMPP
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // XAMPP default user
define('DB_PASS', '');               // XAMPP default (no password)
define('DB_NAME', 'goalpost_db');    // Database name

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("<h2 style='color:red;'>Database Connection Failed</h2>
        <p>Error: " . htmlspecialchars($conn->connect_error) . "</p>
        <p>Make sure:</p>
        <ul>
            <li>XAMPP MySQL is running (check Control Panel)</li>
            <li>Database 'goalpost_db' exists (import schema.sql in phpMyAdmin)</li>
            <li>Database credentials are correct in config.php</li>
        </ul>");
}

mysqli_set_charset($conn, "utf8");
?>
