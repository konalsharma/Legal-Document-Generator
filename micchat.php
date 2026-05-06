<?php
header('Content-Type: application/json');

// 🔐 Your OpenAI API key (replace with your actual key)
$apiKey = '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';

// Read input
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (!$userMessage) {
  echo json_encode(['reply' => 'No message received']);
  exit;
}

// Prepare OpenAI API payload
$payload = [
  'model' => 'gpt-3.5-turbo',
  'messages' => [
    ['role' => 'user', 'content' => $userMessage]
  ]
];

// cURL to OpenAI
$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
  echo json_encode(['reply' => 'cURL error: ' . $error]);
  exit;
}

$responseData = json_decode($response, true);
$reply = $responseData['choices'][0]['message']['content'] ?? 'No response from OpenAI';
echo json_encode(['reply' => $reply]);
