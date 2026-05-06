<?php
$apiKey = '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'; // Replace with your actual key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $rawText = trim($input['text'] ?? '');

    if (!$rawText) {
        echo "❌ No text provided.";
        exit;
    }

    $payload = [
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes documents clearly and accurately.'],
            ['role' => 'user', 'content' => "Please summarize the following document:\n\n$rawText"]
        ],
        'max_tokens' => 1500
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    echo $result['choices'][0]['message']['content'] ?? '❌ GPT did not return a summary.';
} else {
    echo "❌ Invalid request.";
}
