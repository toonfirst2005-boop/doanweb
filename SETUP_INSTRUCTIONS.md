# Hướng dẫn thiết lập đồng bộ trạng thái đơn hàng

## Bước 1: Tạo bảng order_status_log

Chạy script SQL sau trong phpMyAdmin hoặc MySQL:

```sql
-- Tạo bảng để theo dõi thay đổi trạng thái đơn hàng
CREATE TABLE IF NOT EXISTS `order_status_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) NOT NULL,
  `old_status` int(11) DEFAULT NULL,
  `new_status` int(11) NOT NULL,
  `updated_by` varchar(255) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `receipt_id` (`receipt_id`),
  FOREIGN KEY (`receipt_id`) REFERENCES `receipts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm chỉ mục để tối ưu hóa truy vấn
CREATE INDEX idx_receipt_update_time ON order_status_log (receipt_id, update_time);
CREATE INDEX idx_update_time ON order_status_log (update_time);
```

Hoặc import file `create_order_status_log_table.sql`

## Bước 2: Kiểm tra các file đã được tạo

Đảm bảo các file sau đã được tạo/cập nhật:

1. `get_order_status.php` - API lấy trạng thái đơn hàng
2. `check_order_updates.php` - API kiểm tra cập nhật trạng thái
3. `customer_profile.php` - Đã cập nhật với tính năng auto-refresh
4. `admin/receipts/update_receipt.php` - Đã thêm logging

## Bước 3: Test tính năng

1. Đăng nhập với tài khoản khách hàng có đơn hàng
2. Mở trang profile khách hàng (`customer_profile.php`)
3. Từ admin panel, thay đổi trạng thái đơn hàng
4. Kiểm tra xem trang khách hàng có tự động cập nhật và hiển thị thông báo không

## Tính năng mới

### Cho khách hàng:
- ✅ Auto-refresh trạng thái đơn hàng mỗi 30 giây
- ✅ Kiểm tra cập nhật realtime mỗi 10 giây
- ✅ Thông báo popup khi có thay đổi trạng thái
- ✅ Progress bar hiển thị tiến trình đơn hàng
- ✅ UI/UX cải thiện với icon và màu sắc

### Cho admin:
- ✅ Logging mọi thay đổi trạng thái đơn hàng
- ✅ Theo dõi ai đã thay đổi trạng thái và khi nào

## Cách hoạt động

1. Khi admin thay đổi trạng thái đơn hàng, hệ thống sẽ:
   - Cập nhật trạng thái trong bảng `receipts`
   - Ghi log vào bảng `order_status_log`
   - Ghi hoạt động vào `activity_log`

2. Trang khách hàng sẽ:
   - Tự động kiểm tra cập nhật mỗi 10 giây
   - Hiển thị thông báo khi có thay đổi
   - Cập nhật giao diện với trạng thái mới
   - Hiển thị progress bar theo tiến trình

3. API endpoints:
   - `GET get_order_status.php` - Lấy tất cả đơn hàng hoặc đơn hàng cụ thể
   - `GET check_order_updates.php?last_check=YYYY-MM-DD HH:MM:SS` - Kiểm tra cập nhật mới

## Trạng thái đơn hàng

- **Status 2**: Chờ xác nhận (25% progress)
- **Status 3**: Shop đã hủy (0% progress)
- **Status 4**: Đang giao hàng (75% progress)  
- **Status 5**: Hoàn thành (100% progress)
- **Status 7**: Khách hủy (0% progress)
