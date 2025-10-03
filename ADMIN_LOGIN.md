# 🔐 THÔNG TIN ĐĂNG NHẬP ADMIN

## Tài khoản Admin mặc định

### 1. Super Admin (Quản lý cấp cao)
- **Tên đăng nhập:** `superadmin@gmail.com`
- **Mật khẩu:** `qwer1234`
- **Cấp độ:** Super Admin (level 1)
- **Quyền hạn:** Toàn quyền quản lý hệ thống

### 2. Admin (Nhân viên)
- **Tên đăng nhập:** `admin@gmail.com`
- **Mật khẩu:** `qwer1234`
- **Cấp độ:** Nhân viên (level 0)
- **Quyền hạn:** Quyền quản lý cơ bản

---

## 📝 Lưu ý quan trọng

### Bảo mật
⚠️ **QUAN TRỌNG:** Đổi mật khẩu mặc định ngay sau lần đăng nhập đầu tiên!

### Tạo tài khoản Admin mới
- Chỉ Super Admin mới có quyền tạo tài khoản admin mới
- Tài khoản admin mới sẽ có định dạng: `username@admin.com`
- Ví dụ: Nhập `admin123` → Tài khoản: `admin123@admin.com`
- Cần xác nhận mật khẩu của người tạo để bảo mật

### Yêu cầu mật khẩu
- Tối thiểu 6 ký tự
- Được mã hóa bằng MD5
- Phải xác nhận 2 lần khi tạo mới

---

## 🚀 Hướng dẫn đăng nhập

1. Truy cập: `http://localhost/banhang/admin/`
2. Nhập tên đăng nhập: `superadmin@gmail.com`
3. Nhập mật khẩu: `qwer1234`
4. Click "Đăng nhập"

---

## 🔧 Khôi phục mật khẩu

Nếu quên mật khẩu, có thể reset bằng cách:

### Cách 1: Qua phpMyAdmin
1. Mở phpMyAdmin
2. Chọn database `banhang`
3. Chọn bảng `admins`
4. Tìm tài khoản cần reset
5. Sửa cột `password` thành: `81dc9bdb52d04dc20036dbd8313ed055` (MD5 của "1234")
6. Đăng nhập với mật khẩu mới: `1234`

### Cách 2: Chạy SQL
```sql
UPDATE admins 
SET password = '81dc9bdb52d04dc20036dbd8313ed055' 
WHERE name = 'superadmin@gmail.com';
```

---

## 📊 Cấu trúc bảng admins

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | INT | ID tự tăng |
| name | VARCHAR | Tên đăng nhập (email) |
| email | VARCHAR | Email (có thể trùng với name) |
| password | VARCHAR | Mật khẩu đã mã hóa MD5 |
| level | INT | 0: Nhân viên, 1: Super Admin |
| status | INT | 1: Hoạt động, 0: Bị khóa |

---

## 🔑 Mã MD5 một số mật khẩu thông dụng

Để test hoặc khôi phục nhanh:

| Mật khẩu | MD5 Hash |
|----------|----------|
| 123456 | e10adc3949ba59abbe56e057f20f883e |
| 1234 | 81dc9bdb52d04dc20036dbd8313ed055 |
| qwer1234 | 5a105e8b9d40e1329780d62ea2265d8a |
| admin123 | 0192023a7bbd73250516f069df18b500 |

---

**Ngày tạo:** 02/10/2025  
**Phiên bản:** 1.0
