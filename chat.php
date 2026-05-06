<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// MySQL connection
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'reply' => 'DB Connection failed: ' . $conn->connect_error]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";
$documentText = $input["document"] ?? "";
$session_id = intval($input["session_id"] ?? 1);
$action = $input["action"] ?? "";

// For testing, use a fixed user_id. In production, use $_SESSION["user_id"]
$user_id = 1; // $_SESSION["user_id"] ?? 1;

// Get chat history
if ($action === "get_history") {
    $history = [];
    $stmt = $conn->prepare("SELECT role, content, timestamp FROM chats WHERE user_id=? AND session_id=? ORDER BY timestamp ASC");
    $stmt->bind_param("ii", $user_id, $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    $stmt->close();
    
    echo json_encode(["success" => true, "history" => $history]);
    exit;
}

if (empty($userMessage) && empty($documentText)) {
    echo json_encode(["success" => false, "reply" => "No input provided"]);
    exit;
}

// Handle document upload - save immediately
if (!empty($documentText)) {
    $stmt = $conn->prepare("INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'assistant', ?)");
    $docContent = "📄 Document Summary: " . $documentText;
    $stmt->bind_param("iis", $user_id, $session_id, $docContent);
    
    if ($stmt->execute()) {
        // Document saved successfully
    } else {
        echo json_encode(["success" => false, "reply" => "Failed to save document"]);
        exit;
    }
    $stmt->close();
}

// Save user message
if (!empty($userMessage)) {
    $stmt = $conn->prepare("INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'user', ?)");
    $stmt->bind_param("iis", $user_id, $session_id, $userMessage);
    $stmt->execute();
    $stmt->close();
}

// Simulate AI response (replace with actual OpenAI API if needed)
$reply = "This is a simulated response to: '" . $userMessage . "'. Document context: " . substr($documentText, 0, 100) . "...";

// For actual OpenAI integration, uncomment this section:
/*
$apiKey = 'your-openai-api-key-here';

$messages = [
    ["role" => "system", "content" => "You are a helpful legal AI assistant."],
    ["role" => "user", "content" => "Document: " . $documentText . "\n\nQuestion: " . $userMessage]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-3.5-turbo",
        "messages" => $messages,
        "temperature" => 0.3
    ])
]);

$response = curl_exec($ch);
if (!curl_errno($ch)) {
    $result = json_decode($response, true);
    $reply = $result["choices"][0]["message"]["content"] ?? "Sorry, no response.";
}
curl_close($ch);
*/

// Save assistant reply
$stmt = $conn->prepare("INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'assistant', ?)");
$stmt->bind_param("iis", $user_id, $session_id, $reply);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true, "reply" => $reply]);
$conn->close();
?>