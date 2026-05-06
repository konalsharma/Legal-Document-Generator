<?php
session_start();
header('Content-Type: application/json');

// 🔑 OpenAI API Key
$apiKey = 'YOUR_API_KEY';

// 🔑 DB connection
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die(json_encode(['reply' => 'DB Connection failed: ' . $conn->connect_error]));
}

// 📥 Input
$input   = json_decode(file_get_contents("php://input"), true);
$message = $input["message"] ?? '';
$user_id = $_SESSION["user_id"] ?? null;

if (empty($message)) {
    echo json_encode(["reply" => "No message received"]);
    exit;
}
if (!$user_id) {
    echo json_encode(["reply" => "⚠️ Please log in to continue."]);
    exit;
}

// ✅ Single chat per user (if you want multi-chat, change this)
$chatId = $user_id;

// ✅ Handle OCR replacement
$content = (strpos($message, "[OCR Extracted Text]") === 0) ? "[image]" : $message;

// ✅ Save user message once
$stmt = $conn->prepare("INSERT INTO chatqa (chat_id, user_id, role, content, created_at) VALUES (?, ?, 'user', ?, NOW())");
$stmt->bind_param("iis", $chatId, $user_id, $content);
$stmt->execute();
$stmt->close();

// ✅ Build full history for this chat
$messages = [];
$stmt = $conn->prepare("SELECT role, content FROM chatqa WHERE chat_id=? ORDER BY id ASC");
$stmt->bind_param("i", $chatId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();

// ✅ Add system instructions
array_unshift($messages, [
    'role' => 'system',
    'content' => 'You are a helpful assistant. When a user uploads an image, analyze it and provide a natural, well-structured summary in full sentences.'
]);
array_unshift($messages, [
    'role' => 'system',
    'content' => <<<EOT
You are LawAI, a professional Indian lawyer with strong practical experience.
Act as a mentor for junior lawyers or recent law graduates who ask you questions.
Always explain clearly, give reasoning, and, where relevant, refer to Indian case law and statutory provisions (BNS, BNSS, BSA, CPC, Constitution, NI Act, Contract Act, Property Laws, etc.).

Structure your answers using these sections, but include only those that are relevant to the question:

1. **Summary** – a short, plain-language explanation of the issue.  
2. **Relevant Law / Sections / Case References** – cite provisions of Indian law and landmark precedents, but only if directly applicable.  
3. **Detailed Guidance** – practical explanation as if guiding a junior lawyer: how to apply the law, arguments to use, common mistakes to avoid.  
4. **Process / Next Steps** – step-by-step practical advice (e.g., filing, drafting, remedies).  
EOT
]);

// ✅ Call OpenAI
$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-4o",
        "messages" => $messages,
        "temperature" => 0.5,
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

// ✅ Save assistant reply
$stmt = $conn->prepare("INSERT INTO chatqa (chat_id, user_id, role, content, created_at) VALUES (?, ?, 'assistant', ?, NOW())");
$stmt->bind_param("iis", $chatId, $user_id, $reply);
$stmt->execute();
$stmt->close();

// ✅ Response
echo json_encode([
    "reply"   => $reply,
    "chat_id" => $chatId
]);
