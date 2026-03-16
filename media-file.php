<?php
/**
 * Streams files stored in database table `uploaded_files`.
 */
require_once __DIR__ . '/config.php';

$fileId = (int)($_GET['id'] ?? 0);
if ($fileId <= 0) {
    http_response_code(404);
    exit('File not found');
}

ensureDbFileStorageTable();
$stmt = $pdo->prepare('SELECT mime_type, file_size, content FROM uploaded_files WHERE id = ? LIMIT 1');
$stmt->execute([$fileId]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    http_response_code(404);
    exit('File not found');
}

$content = $file['content'];
$mimeType = $file['mime_type'] ?: 'application/octet-stream';
$size = strlen($content);

header('Content-Type: ' . $mimeType);
header('Accept-Ranges: bytes');
header('Cache-Control: public, max-age=604800');

$range = $_SERVER['HTTP_RANGE'] ?? null;
if ($range && preg_match('/bytes=(\d*)-(\d*)/', $range, $matches)) {
    $start = $matches[1] === '' ? 0 : (int)$matches[1];
    $end = $matches[2] === '' ? ($size - 1) : (int)$matches[2];

    if ($start > $end || $start >= $size) {
        header('Content-Range: bytes */' . $size);
        http_response_code(416);
        exit;
    }

    $end = min($end, $size - 1);
    $length = $end - $start + 1;

    http_response_code(206);
    header('Content-Length: ' . $length);
    header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);

    echo substr($content, $start, $length);
    exit;
}

header('Content-Length: ' . $size);
echo $content;
