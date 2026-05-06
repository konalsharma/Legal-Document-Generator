<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;
$session_id = $_GET["session_id"] ?? null;

$conn = new mysqli("localhost", "root", "", "auth_system");

$result = $conn->query("SELECT file_path FROM ocr_uploads WHERE user_id=$user_id AND session_id='$session_id' ORDER BY created_at ASC");

$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
