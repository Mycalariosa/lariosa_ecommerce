-- Add role column to users table
ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'customer';

-- Update existing users to have appropriate roles
-- Replace 'admin@example.com' with your admin email
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';

-- Add index for faster role-based queries
CREATE INDEX idx_users_role ON users(role); 