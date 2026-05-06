<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    echo json_encode(["error" => "DB Connection failed"]);
    exit;
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION["user_id"];

// ✅ Instead of separate table, generate new session_id
// Just take MAX(session_id) for this user and +1
$result = $conn->query("SELECT IFNULL(MAX(session_id),0)+1 AS new_session FROM chats WHERE user_id=$user_id");
$row = $result->fetch_assoc();
$session_id = $row["new_session"];

// ⚡️ No insert yet → session will be created when first message is saved in chat-backend.php
echo json_encode(["session_id" => $session_id]);
