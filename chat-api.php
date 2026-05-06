<?php
// chat-api.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$summary = $data['summary'] ?? '';
$question = $data['question'] ?? '';

if (!$summary || !$question) {
    echo json_encode(['reply' => 'Missing input.']);
    exit;
}

// Compose prompt
$prompt = "You are a legal assistant. Here is the legal summary:\n\n"
        . $summary . "\n\n"
        . "User has a question about it:\n\n"
        . $question . "\n\n"
        . "Answer clearly and accurately.";

// 🔐 Replace with your actual OpenAI key
$apiKey = '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';

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a legal assistant AI.'],
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.5,
    ]),
]);

$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(['reply' => 'No response from AI.']);
    exit;
}

$result = json_decode($response, true);
$reply = $result['choices'][0]['message']['content'] ?? 'No reply found.';
echo json_encode(['reply' => $reply]);
