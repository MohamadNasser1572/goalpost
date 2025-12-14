<?php
session_start();
require_once '../database/config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == 'login') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            $redirect = ($user['role'] == 'admin') ? 'pages/admin_home.php' : 'pages/user_home.php';
            header("Location: ../$redirect");
            exit();
        } else {
            header("Location: ../index.php?error=Invalid password");
        }
    } else {
        header("Location: ../index.php?error=User not found");
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

elseif ($action == 'register_admin') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $admin_key = $_POST['admin_key'];

    // Check admin secret key
    if ($admin_key !== 'GOALPOST2025') {
        header("Location: ../index.php?error=Invalid admin secret key");
        exit();
    }

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
    $insert = $conn->query("INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed', 'admin')");
    
    if ($insert) {
        header("Location: ../index.php?success=Admin account created! Please login");
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
