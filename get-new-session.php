<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION["user_id"];

// Check current session count
$countResult = $conn->query("
    SELECT COUNT(DISTINCT session_id) as session_count 
    FROM chats 
    WHERE user_id = $user_id
");
$countRow = $countResult->fetch_assoc();
$sessionCount = $countRow["session_count"];

// Set session limit
$sessionLimit = 3;

if ($sessionCount >= $sessionLimit) {
    echo json_encode([
        "error" => "session_limit_reached",
        "message" => "You've reached the maximum number of sessions. Please upgrade to premium.",
        "current_sessions" => $sessionCount,
        "session_limit" => $sessionLimit
    ]);
    exit;
}

// Get next session number
$result = $conn->query("SELECT IFNULL(MAX(session_id),0)+1 AS new_session FROM chats WHERE user_id=$user_id");
$row = $result->fetch_assoc();
$new_session = $row["new_session"];

echo json_encode([
    "next_session_id" => $new_session,
    "current_sessions" => $sessionCount,
    "session_limit" => $sessionLimit
]);
?>