<?php
// Check session
if (!isset($_SESSION)) {
    session_start();
}

// Redirect to login if not authenticated (for protected pages)
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }
}

// Redirect to login if not admin
function requireAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        die("Access denied");
    }
}

// Get user info from database
function getUserInfo($user_id, $conn) {
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    return $result->fetch_assoc();
}
?>
