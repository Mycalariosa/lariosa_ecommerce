-- Add role column to users table if it doesn't exist
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('admin', 'customer') DEFAULT 'customer';

-- Create index on role column for better performance
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);

-- Update existing users to have customer role if role is NULL
UPDATE users SET role = 'customer' WHERE role IS NULL;

-- Create views for admin and customer users
CREATE OR REPLACE VIEW admin_users AS
SELECT * FROM users WHERE role = 'admin';

CREATE OR REPLACE VIEW customer_users AS
SELECT * FROM users WHERE role = 'customer';

-- Add trigger to ensure role is either 'admin' or 'customer'
DELIMITER //
CREATE TRIGGER before_user_insert
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    IF NEW.role NOT IN ('admin', 'customer') THEN
        SET NEW.role = 'customer';
    END IF;
END//

CREATE TRIGGER before_user_update
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    IF NEW.role NOT IN ('admin', 'customer') THEN
        SET NEW.role = 'customer';
    END IF;
END//
DELIMITER ;

-- Optional: Add an example admin user (uncomment and modify as needed)
-- INSERT INTO users (name, email, password, role, created_at, updated_at)
-- VALUES ('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()); 