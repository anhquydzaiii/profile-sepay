<?php
require_once 'db.php'; 
error_reporting(0);
header('Content-Type: application/json');

$api_key = "ANHQUYDEV"; 
$prefix  = "Donate"; 

$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (!$data) die(json_encode(['success' => false, 'msg' => 'No Data']));

// 1. XÁC THỰC BẢO MẬT
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$auth_header = $headers['authorization'] ?? '';
if (empty($api_key) || strpos($auth_header, $api_key) === false) {
    http_response_code(401);
    die(json_encode(['success' => false, 'msg' => 'Unauthorized']));
}

// 2. LẤY DỮ LIỆU CHUẨN SEPAY
$content = $data['content'] ?? ''; 
$amount  = (int)($data['transferAmount'] ?? 0); 
$txnID   = $data['id'] ?? ''; 

if (empty($txnID)) die(json_encode(['success' => false, 'msg' => 'Missing ID']));

// 3. CHỐNG TRÙNG
$check = $conn->prepare("SELECT id FROM nap_tien_log WHERE txn_id = ?");
$check->bind_param("s", $txnID);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    http_response_code(200); 
    die(json_encode(['success' => true, 'msg' => 'Processed']));
}

// 4. BÓC TÁCH TÊN (Lấy mọi thứ sau mã Donate)
// Ví dụ: "Donate1729 ANH QUY" -> $matches[1] = 1729, $matches[2] = ANH QUY
if (preg_match('/' . $prefix . '([0-9]{4})\s+(.+)/u', $content, $matches)) {
    $donate_code = $prefix . $matches[1];
    $sender_name = trim($matches[2]); 
} else if (preg_match('/' . $prefix . '([0-9]{4})/i', $content, $matches)) {
    $donate_code = $prefix . $matches[1];
    $sender_name = "Mạnh thường quân";
} else {
    http_response_code(200);
    die(json_encode(['success' => true, 'msg' => 'Invalid syntax']));
}

// 5. LƯU VÀO DATABASE
$stmt = $conn->prepare("INSERT INTO nap_tien_log (username, amount, content, txn_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("siss", $sender_name, $amount, $donate_code, $txnID);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['success' => true, 'msg' => 'Success']);
}