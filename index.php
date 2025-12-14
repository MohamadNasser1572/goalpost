<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoalPost - Football Community</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>⚽ GoalPost</h1>
            <p class="subtitle">Football Community Platform</p>
            
            <div class="form-tabs">
                <button class="tab-btn active" onclick="switchTab('login')">Login</button>
                <button class="tab-btn" onclick="switchTab('register')">Register as User</button>
                <button class="tab-btn" onclick="switchTab('registerAdmin')">Register as Admin</button>
            </div>

            <!-- Login Form -->
            <form id="loginForm" class="form-content active" method="POST" action="includes/auth.php">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="login_username">Username</label>
                    <input type="text" id="login_username" name="username" required placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="login_password">Password</label>
                    <input type="password" id="login_password" name="password" required placeholder="Enter password">
                </div>
                <button type="submit" class="btn-submit">Login</button>
                <p class="demo-text">Admin: admin / admin123 | User: user / user123</p>
            </form>

            <!-- Register as User Form -->
            <form id="registerForm" class="form-content" method="POST" action="includes/auth.php">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label for="reg_username">Username</label>
                    <input type="text" id="reg_username" name="username" required placeholder="Choose username">
                </div>
                <div class="form-group">
                    <label for="reg_email">Email</label>
                    <input type="email" id="reg_email" name="email" required placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="reg_password">Password</label>
                    <input type="password" id="reg_password" name="password" required placeholder="Create password">
                </div>
                <div class="form-group">
                    <label for="reg_confirm">Confirm Password</label>
                    <input type="password" id="reg_confirm" name="confirm_password" required placeholder="Confirm password">
                </div>
                <button type="submit" class="btn-submit">Register as User</button>
            </form>

            <!-- Register as Admin Form -->
            <form id="registerAdminForm" class="form-content" method="POST" action="includes/auth.php">
                <input type="hidden" name="action" value="register_admin">
                <div class="form-group">
                    <label for="adm_username">Username</label>
                    <input type="text" id="adm_username" name="username" required placeholder="Choose admin username">
                </div>
                <div class="form-group">
                    <label for="adm_email">Email</label>
                    <input type="email" id="adm_email" name="email" required placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="adm_password">Password</label>
                    <input type="password" id="adm_password" name="password" required placeholder="Create password">
                </div>
                <div class="form-group">
                    <label for="adm_confirm">Confirm Password</label>
                    <input type="password" id="adm_confirm" name="confirm_password" required placeholder="Confirm password">
                </div>
                <div class="form-group">
                    <label for="admin_key">🔑 Admin Secret Key</label>
                    <input type="password" id="admin_key" name="admin_key" required placeholder="Enter secret admin key">
                </div>
                <button type="submit" class="btn-submit">Register as Admin</button>
                <p class="demo-text" style="font-size: 0.85em; color: #666;">Secret key: GOALPOST2025</p>
            </form>

            <?php
                if (isset($_GET['error'])) {
                    echo '<p class="error-msg">' . htmlspecialchars($_GET['error']) . '</p>';
                }
                if (isset($_GET['success'])) {
                    echo '<p class="success-msg">' . htmlspecialchars($_GET['success']) . '</p>';
                }
            ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
