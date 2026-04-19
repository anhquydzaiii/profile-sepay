# 🚀 Premium Donate Profile V2.1

Giao diện Profile cá nhân tích hợp hệ thống ủng hộ (Donate) tự động qua mã QR ngân hàng.

## ✨ Tính năng
- **Mobile First:** Chống zoom khi gõ phím trên điện thoại.
- **Bento Design:** Phong cách hiện đại, mượt mà.
- **Auto Check:** Tự động kiểm tra lịch sử giao dịch qua nội dung `Donate1629 + TÊN`.
- **Expertise Skills:** Thanh kỹ năng hiển thị trình độ chuyên môn.

## ⚙️ Cấu hình nhanh
1. Chỉnh sửa thông tin tại mảng `$admin` trong file `index.php`.
2. Tạo database và import bảng `nap_tien_log`.
3. Chỉnh sửa kết nối tại file `db.php`.

## 📜 SQL Database
```sql
CREATE TABLE `nap_tien_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
