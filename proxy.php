<?php
// Get the external URL
$url = isset($_GET['url']) ? $_GET['url'] : '';
if (empty($url)) {
    die("No URL provided");
}

// Fetch external content
$options = [
    "http" => [
        "header" => "User-Agent: PHP\r\n"
    ]
];
$context = stream_context_create($options);
$content = file_get_contents($url, false, $context);

// Optional: fix relative URLs (images, CSS, JS) to absolute URLs
$base = parse_url($url);
$baseUrl = $base['scheme'] . '://' . $base['host'];
$content = preg_replace('/(href|src)="(?!http)([^"]+)"/i', '$1="'.$baseUrl.'/$2"', $content);

echo $content;
?>
