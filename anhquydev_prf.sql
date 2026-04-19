-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th4 19, 2026 lúc 09:14 PM
-- Phiên bản máy phục vụ: 10.11.14-MariaDB-cll-lve
-- Phiên bản PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `anhquydev_prf`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nap_tien_log`
--

CREATE TABLE `nap_tien_log` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `txn_id` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `content` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nap_tien_log`
--

INSERT INTO `nap_tien_log` (`id`, `username`, `amount`, `txn_id`, `created_at`, `content`) VALUES
(5, 'Mạnh thường quân', 0, 'FT26110000880300', '2026-04-19 18:20:08', 'Donate3974'),
(6, 'Mạnh thường quân', 0, 'FT26110017025680', '2026-04-19 18:20:12', 'Donate1578'),
(7, 'ANH QUY', 10000, '52039707', '2026-04-19 18:32:47', 'Donate1000'),
(8, 'Mạnh thường quân', 10000, '52040247', '2026-04-19 18:44:37', 'Donate5567'),
(9, 'ANH QUY', 10000, '52040569', '2026-04-19 18:51:52', 'Donate5830'),
(10, 'ANH QUY', 10000, '52040802', '2026-04-19 18:58:02', 'Donate5894'),
(11, 'DUONG TIEN DAT', 10000, '52040952', '2026-04-19 19:02:10', 'Donate7919');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `nap_tien_log`
--
ALTER TABLE `nap_tien_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `nap_tien_log`
--
ALTER TABLE `nap_tien_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
