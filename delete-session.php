<?php
header('Content-Type: application/json');

// DB connection
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => $conn->connect_error]);
    exit;
}

// Get session_id
$session_id = $_GET['id'] ?? 0;
$session_id = intval($session_id);

if ($session_id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid session ID"]);
    exit;
}

// Delete all messages related to this session
$stmt = $conn->prepare("DELETE FROM chats WHERE session_id = ?");
$stmt->bind_param("i", $session_id);
$ok = $stmt->execute();
$stmt->close();

echo json_encode(["success" => $ok]);
$conn->close();
?>