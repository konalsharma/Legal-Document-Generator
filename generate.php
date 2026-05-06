<?php
$apiKey = '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'; // Store securely (ideally in environment variables)

// Get JSON input from frontend
$input = json_decode(file_get_contents("php://input"), true);
$prompt = $input["prompt"] ?? '';

if (!$prompt) {
  http_response_code(400);
  echo json_encode(["error" => "No prompt provided."]);
  exit;
}

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $apiKey
]);

$data = [
  "model" => "gpt-4o",
  "messages" => [
    ["role" => "system", "content" => "You are a legal assistant. Your output must only be the final legal text with no disclaimers or extra notes."],
    ["role" => "user", "content" => $prompt]
  ]
];

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
  http_response_code(500);
  echo json_encode(["error" => curl_error($ch)]);
} else {
  echo $response;
}

curl_close($ch);
