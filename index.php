<?php
// Force no-cache in browsers and proxies
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoalPost - Football Community</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" href="assets/css/style.css?v=4">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>⚽ GoalPost</h1>
            <p class="subtitle">Football Community Platform</p>
            
            <div class="form-tabs">
                <button class="tab-btn active" onclick="switchTab('login')">Login</button>
                <button class="tab-btn" onclick="switchTab('register')">Register</button>
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
            </form>

            <!-- Register Form (users only) -->
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
                <button type="submit" class="btn-submit">Register</button>
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

    <script src="assets/js/main.js?v=4"></script>
    <style>.login-box .demo-text, .login-box footer { display: none !important; }</style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
          document.querySelectorAll('.login-box .demo-text, .login-box footer').forEach(el => el.remove());
          document.querySelectorAll('.login-box p').forEach(el => {
            const t = el.textContent || '';
            if (/admin123|user123|Admin:|User:/i.test(t)) el.remove();
          });
        });
    </script>
</body>
</html>