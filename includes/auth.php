<?php
// Scope session cookie to the app path before starting the session
if (!headers_sent()) {
    ini_set('session.cookie_path', '/GoalPost');
    session_set_cookie_params([
        'path' => '/GoalPost',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}
session_start();
require_once '../database/config.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action == 'login') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (!empty($username) && !empty($password)) {
        $username = $conn->real_escape_string($username);
        $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password == $user['password']) {  // Changed === to == for compatibility
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Simple redirect based on role
                if ($user['role'] == 'admin' || $user['role'] == 'super_admin') {
                    header("Location: ../pages/admin_home.php");
                } else {
                    header("Location: ../pages/user_home.php");
                }
                exit();
            } else {
                header("Location: ../index.php?error=Invalid password");
                exit();
            }
        } else {
            header("Location: ../index.php?error=User not found");
            exit();
        }
    } else {
        header("Location: ../index.php?error=Username and password required");
        exit();
    }
}

elseif ($action == 'register') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        header("Location: ../index.php?error=Passwords do not match");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: ../index.php?error=Password must be at least 6 characters");
        exit();
    }

    $check = $conn->query("SELECT id FROM users WHERE username = '$username' OR email = '$email'");
    if ($check->num_rows > 0) {
        header("Location: ../index.php?error=Username or email already exists");
        exit();
    }

    $hashed = $password;
    $insert = $conn->query("INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed', 'user')");
    
    if ($insert) {
        header("Location: ../index.php?success=Registration successful! Please login");
    } else {
        header("Location: ../index.php?error=Registration failed");
    }
}

elseif ($action == 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>
