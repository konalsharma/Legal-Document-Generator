<?php
session_start();
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "auth_system");

$user_id = $_SESSION["user_id"] ?? null;
$session_id = $_GET["session_id"] ?? null;

if (!$user_id || !$session_id) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT role, content 
    FROM chats 
    WHERE user_id=? AND session_id=? 
    ORDER BY created_at ASC
");
$stmt->bind_param("ii", $user_id, $session_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
echo json_encode($messages);
?>