<?php
session_start();
$user_id = $_SESSION["user_id"] ?? null;
$session_id = $_POST["session_id"] ?? null;

if (!$user_id || !$session_id) {
    echo json_encode(["error" => "Not logged in or session missing"]);
    exit;
}

if (!isset($_FILES["file"])) {
    echo json_encode(["error" => "No file uploaded"]);
    exit;
}

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$filename = time() . "_" . basename($_FILES["file"]["name"]);
$targetFile = $targetDir . $filename;

if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    $conn = new mysqli("localhost", "root", "", "auth_system");
    if ($conn->connect_error) {
        die(json_encode(["error" => "DB error: " . $conn->connect_error]));
    }

    $stmt = $conn->prepare("INSERT INTO ocr_uploads (user_id, session_id, file_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $session_id, $targetFile);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "file_path" => $targetFile
    ]);
} else {
    echo json_encode(["error" => "Failed to upload"]);
}
