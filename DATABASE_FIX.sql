-- Database Fix Script for C19 Banking
-- Run this in phpMyAdmin to ensure proper setup

-- 1. Create test admin user (if not exists)
INSERT INTO users (id, created_by_id, branch_id, name, username, email, password, first_name, last_name, created_at, updated_at) 
VALUES (
    2,
    NULL,
    1,
    'Admin User',
    'admin',
    'admin@c19banking.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Admin',
    'User',
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE email = email;

-- Password for above user is: password

-- 2. Verify branches table has active column (should already exist)
-- SELECT * FROM branches LIMIT 1;

-- 3. Verify loan_products table has active column (should already exist)
-- SELECT * FROM loan_products LIMIT 1;

-- 4. Verify savings_products table has active column (should already exist)
-- SELECT * FROM savings_products LIMIT 1;

-- 5. Check if we have sample data
SELECT 
    (SELECT COUNT(*) FROM branches) as branches_count,
    (SELECT COUNT(*) FROM users) as users_count,
    (SELECT COUNT(*) FROM clients) as clients_count,
    (SELECT COUNT(*) FROM loan_products) as loan_products_count,
    (SELECT COUNT(*) FROM savings_products) as savings_products_count;

-- If you need to create a sample branch (if none exists):
-- INSERT INTO branches (id, name, open_date, active, created_at, updated_at)
-- VALUES (1, 'Main Branch', CURDATE(), 1, NOW(), NOW())
-- ON DUPLICATE KEY UPDATE name = name;
