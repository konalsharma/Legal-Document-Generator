<?php
session_start();
header("Content-Type: application/json");

// 🔑 OpenAI API Key
$apiKey = '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';

// ✅ MySQL connection
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die(json_encode(['reply' => 'DB Connection failed: ' . $conn->connect_error]));
}

// ✅ Read JSON input
$input        = json_decode(file_get_contents("php://input"), true);
$userMessage  = $input["message"] ?? "";
$documentText = $input["document"] ?? "";
$session_id   = intval($input["session_id"] ?? 0);
$user_id      = $_SESSION["user_id"] ?? null;

if (!$user_id) {
    echo json_encode(["reply" => "⚠️ No user logged in"]);
    exit;
}

if (empty($userMessage) && empty($documentText)) {
    echo json_encode(["reply" => "⚠️ No input provided"]);
    exit;
}

// ✅ Always ensure doc is stored once per session
if (!empty($documentText)) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM chats WHERE user_id=? AND session_id=? AND role='assistant'");
    $stmt->bind_param("ii", $user_id, $session_id);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();

    if ($cnt == 0) {
        $stmt = $conn->prepare("INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'assistant', ?)");
        $stmt->bind_param("iis", $user_id, $session_id, $documentText);
        $stmt->execute();
        $stmt->close();
    }
}

// ✅ Build prompt
$prompt = "You are Speed AI Assistant. The user uploaded this case document:\n\n" .
          $documentText .
          "\n\nNow the user asks: " . $userMessage;

// ✅ Prepare messages
$messages = [
    ["role" => "system", "content" => "You are a helpful legal AI assistant."],
    ["role" => "user", "content" => $prompt]
];

// ✅ Call OpenAI
$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-4o-mini",
        "messages" => $messages,
        "temperature" => 0.3
    ])
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["reply" => "Curl error: " . curl_error($ch)]);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);
$reply  = $result["choices"][0]["message"]["content"] ?? "⚠️ Sorry, no response.";

// ✅ Save user message
// ✅ Save user message(s)
if (!empty($userMessage)) {
    if (strpos($userMessage, "[OCR Extracted Text]") === 0) {
        // Case: Only OCR input (no manual text)
        $stmt = $conn->prepare(
            "INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'user', '[image]')"
        );
        $stmt->bind_param("ii", $user_id, $session_id);
        $stmt->execute();
        $stmt->close();
    } elseif (strpos($userMessage, "[OCR Extracted Text]") !== false) {
        // Case: Mixed input → OCR + manual text together
        // 👉 Split them: save OCR as [image], and keep the text part
        $parts = explode("[OCR Extracted Text]", $userMessage, 2);
        $manualText = trim($parts[0]);
        $ocrText    = trim($parts[1]);

        if (!empty($manualText)) {
            $stmt = $conn->prepare(
                "INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'user', ?)"
            );
            $stmt->bind_param("iis", $user_id, $session_id, $manualText);
            $stmt->execute();
            $stmt->close();
        }

        // Always store OCR part as [image]
        $stmt = $conn->prepare(
            "INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'user', '[image]')"
        );
        $stmt->bind_param("ii", $user_id, $session_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Case: Only normal text
        $stmt = $conn->prepare(
            "INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'user', ?)"
        );
        $stmt->bind_param("iis", $user_id, $session_id, $userMessage);
        $stmt->execute();
        $stmt->close();
    }
}


// ✅ Save assistant reply
$stmt = $conn->prepare("INSERT INTO chats (user_id, session_id, role, content) VALUES (?, ?, 'assistant', ?)");
$stmt->bind_param("iis", $user_id, $session_id, $reply);
$stmt->execute();
$stmt->close();

echo json_encode(["reply" => $reply]);
