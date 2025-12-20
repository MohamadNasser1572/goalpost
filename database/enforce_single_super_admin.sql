-- Demote all super_admins except the chosen one (edit username)
UPDATE users SET role='admin' WHERE role='super_admin' AND username <> 'superadmin';

-- Triggers to enforce that only one super_admin can exist
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
