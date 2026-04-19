<?php
$host = 'localhost';
$user = 'anhquydev_prf';
$pass = 'anhquydev_prf'; // Mật khẩu database
$db   = 'anhquydev_prf';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>