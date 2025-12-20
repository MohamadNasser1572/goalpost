-- GoalPost Database Schema
CREATE DATABASE IF NOT EXISTS goalpost_db;
USE goalpost_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'super_admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Matches Table
CREATE TABLE IF NOT EXISTS matches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team1 VARCHAR(100) NOT NULL,
    team2 VARCHAR(100) NOT NULL,
    date_match DATETIME NOT NULL,
    score_team1 INT DEFAULT NULL,
    score_team2 INT DEFAULT NULL,
    status ENUM('upcoming', 'live', 'finished') DEFAULT 'upcoming',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Comments Table
CREATE TABLE IF NOT EXISTS comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Sample Admin User (Password: admin123)
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@goalpost.com', 'admin123', 'admin');

-- Insert Sample Regular User (Password: user123)
INSERT INTO users (username, email, password, role) 
VALUES ('user', 'user@goalpost.com', 'user123', 'user');

-- Insert Super Admin (Password: super123)
INSERT INTO users (username, email, password, role)
VALUES ('superadmin', 'super@goalpost.com', 'super123', 'super_admin');

-- Insert Sample Matches
INSERT INTO matches (team1, team2, date_match, status, created_by) 
VALUES 
('Manchester United', 'Liverpool', '2025-12-20 15:00:00', 'upcoming', 1),
('Real Madrid', 'Barcelona', '2025-12-21 20:00:00', 'upcoming', 1),
('Bayern Munich', 'Borussia Dortmund', '2025-12-22 18:30:00', 'upcoming', 1);

-- Enforce single super_admin with triggers
DROP TRIGGER IF EXISTS users_before_insert_super_admin;
DROP TRIGGER IF EXISTS users_before_update_super_admin;
DELIMITER $$
CREATE TRIGGER users_before_insert_super_admin
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    IF NEW.role = 'super_admin' THEN
        IF (SELECT COUNT(*) FROM users WHERE role='super_admin') > 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Only one super_admin allowed';
        END IF;
    END IF;
END$$

CREATE TRIGGER users_before_update_super_admin
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    IF NEW.role = 'super_admin' THEN
        IF (SELECT COUNT(*) FROM users WHERE role='super_admin' AND id <> OLD.id) > 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Only one super_admin allowed';
        END IF;
    END IF;
END$$
DELIMITER ;
