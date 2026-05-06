<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die(json_encode(['error' => 'DB Connection failed']));
}

// 🔑 You must have a logged-in user
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode([]);
    exit;
}

// ✅ Get distinct chats for this user
$sql = "
    SELECT c.chat_id, 
           MIN(c.created_at) AS started_at,
           (SELECT content 
            FROM chatqa 
            WHERE chat_id = c.chat_id AND role='user' 
            ORDER BY id ASC LIMIT 1) AS title
    FROM chatqa c
    WHERE c.user_id = ?
    GROUP BY c.chat_id
    ORDER BY started_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$chats = [];
while ($row = $result->fetch_assoc()) {
    $chats[] = [
        "id"    => (int)$row['chat_id'],
        "title" => $row['title'] ?: "New Chat"
    ];
}

echo json_encode($chats);
