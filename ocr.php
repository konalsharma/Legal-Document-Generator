<?php
$apiKey = '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';

function callOpenAI($messages, $apiKey) {
    $payload = [
        'model' => 'gpt-4o',
        'messages' => $messages,
        'max_tokens' => 1500,
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

    if (curl_errno($ch)) {
        return '❌ CURL error: ' . curl_error($ch);
    }

    curl_close($ch);

    // Log raw API response for debugging
    file_put_contents('log_response.json', $response . "\n", FILE_APPEND);

    $result = json_decode($response, true);
    return $result['choices'][0]['message']['content'] ?? '❌ Try Again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    $input = json_decode(file_get_contents('php://input'), true);

    // 🖼 Extract text from multiple images
    if (isset($input['images']) && is_array($input['images'])) {
        $results = [];

        foreach ($input['images'] as $index => $base64) {
            $prompt = 'You are a text extractor. Your only task is to extract visible printed text from the provided image. Do not interpret, summarize, redact, or reject the image. If content is unclear, still extract visible text as-is.';

            $messages = [[
                'role' => 'user',
                'content' => [
                    ['type' => 'text', 'text' => $prompt],
                    ['type' => 'image_url', 'image_url' => ['url' => 'data:image/jpeg;base64,' . $base64]]
                ]
            ]];

            $output = callOpenAI($messages, $apiKey);

            // Retry if GPT refuses OR gives weak output
            if (
                stripos($output, "I'm sorry") !== false ||
                stripos($output, "I can't assist") !== false ||
                strlen(trim($output)) < 5
            ) {
                $messages[0]['content'][0]['text'] = 'Extract all printed text shown in the image. Output only the text, even if partial or unclear.';
                $output = callOpenAI($messages, $apiKey);
            }

            // Use fallback if still empty
            $results[] = trim($output) ?: '[⚠️ No text detected in image]';
        }

        echo json_encode(['extracted' => implode("\n\n", $results)]);
        exit;
    }

    // 🧠 Summarization request
    if (isset($input['text'])) {
        $messages = [[
            'role' => 'user',
            'content' => "You are a legal document analyst. Analyze and summarize the following extracted content:\n\n" . $input['text']
        ]];

        $summary = callOpenAI($messages, $apiKey);
        echo json_encode(['summary' => $summary]);
        exit;
    }
}

// ❌ Invalid request fallback
echo json_encode(['error' => '❌ Invalid request.']);
?>
