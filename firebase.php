<?php
include "include/connect.php";

// Get token from request
$token = isset($_POST['token']) ? trim($_POST['token']) : '';

if (!empty($token)) {
    // Prevent duplicate tokens
    $stmt = $conn->prepare("SELECT id FROM fcm_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Insert new token
        $stmt = $conn->prepare("INSERT INTO fcm_tokens (token) VALUES (?)");
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            echo "Token saved successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Token already exists.";
    }

    $stmt->close();
} else {
    echo "No token received.";
}

$conn->close();
?>
