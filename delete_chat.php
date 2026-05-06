<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => $conn->connect_error]));
}

$chat_id = intval($_GET["id"] ?? 0);

if ($chat_id > 0) {
    $stmt = $conn->prepare("DELETE FROM chatqa WHERE chat_id = ?");
    $stmt->bind_param("i", $chat_id);
    $ok = $stmt->execute();
    $stmt->close();
    echo json_encode(["success" => $ok]);
} else {
    echo json_encode(["success" => false, "error" => "Invalid chat id"]);
}
?>