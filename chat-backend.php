<?php
session_start();
header('Content-Type: application/json');

$apiKey = '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';

// ✅ MySQL connection
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die(json_encode(['reply' => 'DB Connection failed: ' . $conn->connect_error]));
}

$input      = json_decode(file_get_contents("php://input"), true);
$messages   = $input["messages"] ?? [];
$session_id = $input["session_id"] ?? null;
$user_id    = $_SESSION["user_id"] ?? null;

if (!$user_id || !$session_id) {
    echo json_encode(["reply" => "No user/session"]);
    exit;
}

if (empty($messages)) {
    echo json_encode(["reply" => "No messages received"]);
    exit;
}

// ✅ Add system instructions at the beginning
array_unshift($messages, [
    'role' => 'system',
    'content' => 'You are a helpful assistant. When a user uploads an image (with extracted OCR text), analyze it and provide a natural, well-structured summary in full sentences.'
]);

array_unshift($messages, [
    'role' => 'system',
    'content' => 'You are a legal assistant specialized in Indian criminal law (BNS – Bharatiya Nyaya Sanhita, 2023). The user may provide a short description of an incident, crime, or situation. Your task is to:
1. Identify the relevant section(s) of the BNS that may apply.
2. For each section, give:
   - Section Number & Title
   - Short Explanation of why it applies
3. If multiple sections could apply, list all possible ones.
4. Clearly mention that applicability may depend on case details.
5. Always add a disclaimer: "This is an AI-generated suggestion for educational purposes. Please verify with the BNS text or consult a lawyer."

Format answers like this:

**Possible Sections:**
- Section [Number] – [Name]  
  Explanation: [Short reason]'
]);

// ✅ Call OpenAI API
$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-4.1-mini",
        "messages" => $messages,
        "temperature" => 0.7,
        "max_tokens" => 800
    ])
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["reply" => "Curl error: " . curl_error($ch)]);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);
$reply  = $result["choices"][0]["message"]["content"] ?? "No reply";

// ✅ Save last user message (skip OCR dumps)
$lastMsg = end($messages);
if ($lastMsg["role"] === "user") {
    $content = $lastMsg["content"];

    if (strpos($content, "[OCR Extracted Text]") === 0) {
        // OCR text → store as [image]
        $stmt = $conn->prepare(
            "INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'user', '[image]')"
        );
        $stmt->bind_param("ii", $user_id, $session_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Normal text → store as-is
        $stmt = $conn->prepare(
            "INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'user', ?)"
        );
        $stmt->bind_param("iis", $user_id, $session_id, $content);
        $stmt->execute();
        $stmt->close();
    }
}

// ✅ Save assistant reply
$stmt = $conn->prepare("INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'assistant', ?)");
$stmt->bind_param("iis", $user_id, $session_id, $reply);
$stmt->execute();

echo json_encode(["reply" => $reply]);
