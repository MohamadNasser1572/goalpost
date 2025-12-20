-- Add super_admin as a role option
-- Modify users table to support 'super_admin' role (no schema change needed)
-- Upgrade one existing admin to super_admin

-- Find an admin user and promote them to super_admin
-- Replace 'admin_username' with your actual admin username
UPDATE users SET role = 'super_admin' WHERE username = 'admin_username' LIMIT 1;

-- If no admin exists yet, create a super_admin account
-- Uncomment and modify these lines:
-- INSERT INTO users (username, email, password, role) VALUES 
-- ('superadmin', 'super@admin.com', '$2y$10$hash_here', 'super_admin');

-- Note: You'll need to run this SQL in phpMyAdmin or MySQL client
-- Make sure to replace 'admin_username' with your actual username
