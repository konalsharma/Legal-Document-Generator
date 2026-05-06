<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$apiKey = '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'; // 🔑 Put your OpenAI API key here

function callOpenAI($messages, $apiKey) {
    $payload = [
        'model' => 'gpt-4o',
        'messages' => $messages,
        'max_tokens' => 2000,
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['choices'][0]['message']['content'] ?? '❌ No response from GPT.';
}

// 🧠 Summarize extracted text
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    $input = json_decode(file_get_contents("php://input"), true);
    $text = $input['text'] ?? '';

    if (!$text) {
        echo "❌ No text to summarize.";
        exit;
    }

    $messages = [[
        'role' => 'user',
        'content' => "You are a legal document analyst. Analyze the following legal content and provide a combined summary, important points, and any potential legal issues:\n\n" . $text
    ]];

    echo callOpenAI($messages, $apiKey);
    exit;
}

// 🖼 Extract text from multiple images
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $images = $_FILES['images'];
    $allText = "";

    for ($i = 0; $i < count($images['tmp_name']); $i++) {
        $tmpName = $images['tmp_name'][$i];
        if (!file_exists($tmpName)) continue;

        $imageData = base64_encode(file_get_contents($tmpName));
        $mime = mime_content_type($tmpName);

        $messages = [[
            'role' => 'user',
            'content' => [
                ['type' => 'text', 'text' => 'Extract all visible text from this document image. Do not summarize it.'],
                ['type' => 'image_url', 'image_url' => ['url' => "data:$mime;base64,$imageData"]]
            ]
        ]];

        $text = callOpenAI($messages, $apiKey);
        $allText .= "\n\n--- Document " . ($i+1) . " ---\n" . $text;
    }

    echo $allText;
    exit;
}

echo "❌ Invalid request.";
?>
