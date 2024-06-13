<?php
session_start();
include('database.php');

// Kullanıcı oturumunun doğrulanması
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

// Mesaj gönderme işlemi
$stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)");
$stmt->bindValue(':sender_id', $sender_id);
$stmt->bindValue(':receiver_id', $receiver_id);
$stmt->bindValue(':message', $message);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
}
?>
