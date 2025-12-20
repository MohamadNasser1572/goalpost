<?php
// Ensure session cookies are scoped to the app path
if (!headers_sent()) {
    ini_set('session.cookie_path', '/GoalPost');
    session_set_cookie_params([
        'path' => '/GoalPost',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}
// Check session
if (session_status() === PHP_SESSION_NONE) {
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
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
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
