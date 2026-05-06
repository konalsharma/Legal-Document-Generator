<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die(json_encode(['error' => 'DB Connection failed']));
}

session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode([]);
    exit;
}

// Get all messages for this user, ordered by ID (chronological)
$stmt = $conn->prepare("SELECT role, content FROM chatqa WHERE user_id=? ORDER BY id ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        "role"    => $row["role"],    // "user" or "assistant"
        "content" => $row["content"]
    ];
}

echo json_encode($messages);
