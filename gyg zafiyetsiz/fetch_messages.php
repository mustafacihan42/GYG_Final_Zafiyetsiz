<?php
session_start();
include('database.php');

// Kullanıcı oturumunun doğrulanması
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$other_user_id = $_GET['other_user_id'];

// Mesajları getirme işlemi
$stmt = $db->prepare("SELECT * FROM messages WHERE (sender_id = :user_id AND receiver_id = :other_user_id) OR (sender_id = :other_user_id AND receiver_id = :user_id) ORDER BY timestamp");
$stmt->bindValue(':user_id', $user_id);
$stmt->bindValue(':other_user_id', $other_user_id);
$result = $stmt->execute();

$messages = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $messages[] = $row;
}

echo json_encode(['status' => 'success', 'messages' => $messages]);
?>
