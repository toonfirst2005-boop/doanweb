-- Tạo tài khoản admin mới
-- Chạy SQL này trong phpMyAdmin hoặc MySQL

-- Xóa admin cũ nếu có (optional)
-- DELETE FROM admins WHERE name = 'testadmin@admin.com';

-- Tạo admin mới với đầy đủ thông tin
INSERT INTO `admins` (`name`, `email`, `password`, `level`, `status`) 
VALUES 
('testadmin@admin.com', 'testadmin@admin.com', '5a105e8b9d40e1329780d62ea2265d8a', 1, 1);

-- Giải thích:
-- name: testadmin@admin.com (tên đăng nhập)
-- email: testadmin@admin.com (email)
-- password: 5a105e8b9d40e1329780d62ea2265d8a (MD5 của "qwer1234")
-- level: 1 (Super Admin)
-- status: 1 (Active)

-- Hoặc tạo nhiều admin:
INSERT INTO `admins` (`name`, `email`, `password`, `level`, `status`) 
VALUES 
('admin1@admin.com', 'admin1@admin.com', '5a105e8b9d40e1329780d62ea2265d8a', 1, 1),
('admin2@admin.com', 'admin2@admin.com', '5a105e8b9d40e1329780d62ea2265d8a', 0, 1),
('admin3@admin.com', 'admin3@admin.com', '5a105e8b9d40e1329780d62ea2265d8a', 0, 1);

-- Kiểm tra admin đã tạo:
SELECT * FROM admins WHERE name LIKE '%@admin.com';

-- Reset mật khẩu admin hiện có:
UPDATE admins SET password = '5a105e8b9d40e1329780d62ea2265d8a' WHERE name = 'superadmin@gmail.com';
