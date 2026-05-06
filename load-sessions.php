<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "auth_system");

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit;
}

$user_id = intval($_SESSION["user_id"]);

// ✅ Get sessions with custom titles
$sql = "
    SELECT session_id, MAX(title) AS title, MIN(created_at) AS first_time
    FROM chats
    WHERE user_id = $user_id
    GROUP BY session_id
    ORDER BY first_time DESC
";

$result = $conn->query($sql);

$sessions = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sessions[] = [
            "id" => $row["session_id"],
            "title" => !empty($row["title"]) ? $row["title"] : "Case " . $row["session_id"]
        ];
    }
}

echo json_encode($sessions);
