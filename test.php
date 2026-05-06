<?php
include "include/connect.php";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$url = '';

if ($id > 0) {
    $stmt = $conn->prepare("SELECT url FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($url);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>News Article</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
}
.iframe-container {
    width: 100%;
}
.iframe-container iframe {
    width: 100%;
    border: none;
	height: 630px;
}
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 10;
    background: transparent; /* block clicks */
}
.iframe-container iframe {
    width: 100%;
    border: none;
	height: 630px;
}
@media (max-width: 768px) {
    .iframe-container iframe {
        height: 830px; /* larger height on mobile */
    }
}
</style>
</head>
<body>
<?php if (!empty($url)): ?>
<div class="iframe-container" style="position:relative;">
    <iframe id="newsFrame" src="https://lawai.guru/proxy.php?url=<?php echo urlencode($url); ?>"></iframe>
    <div class="overlay"></div>
</div>

<script>
// Auto-resize iframe
const iframe = document.getElementById('newsFrame');
iframe.onload = function() {
    try {
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        iframe.style.height = doc.body.scrollHeight + 'px';
    } catch(e) {
        // If external site is still cross-origin, fallback height
        iframe.style.height = '150vh';
    }
};
</script>

<?php else: ?>
<p style="padding:15px;">Invalid news ID or URL not found.</p>
<?php endif; ?>
</body>
</html>