<?php
/**
 * Streams files stored in database table `uploaded_files`.
 * Meta întâi + ETag → 304 fără a încărca blob-ul în RAM (mult mai rapid la revisitări).
 */
require_once __DIR__ . '/config.php';

$fileId = (int)($_GET['id'] ?? 0);
if ($fileId <= 0) {
    http_response_code(404);
    exit('File not found');
}

ensureDbFileStorageTable();

$stmt = $pdo->prepare('SELECT mime_type, file_size FROM uploaded_files WHERE id = ? LIMIT 1');
$stmt->execute([$fileId]);
$meta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$meta) {
    http_response_code(404);
    exit('File not found');
}

$mimeType = $meta['mime_type'] ?: 'application/octet-stream';
$size = (int) ($meta['file_size'] ?? 0);

// ETag stabil: id + dimensiune + tip (fără a citi LONGBLOB)
$etag = '"' . md5($fileId . '|' . $size . '|' . $mimeType) . '"';
header('ETag: ' . $etag);
header('Cache-Control: public, max-age=604800, immutable');

$range = $_SERVER['HTTP_RANGE'] ?? null;
$ifNoneMatch = $_SERVER['HTTP_IF_NONE_MATCH'] ?? null;

// 304: browser are deja fișierul — evită citirea blob din MySQL
if ($range === null && $ifNoneMatch !== null) {
    $inm = trim($ifNoneMatch);
    if ($inm === $etag || str_replace('W/', '', $inm) === $etag) {
        http_response_code(304);
        exit;
    }
}

$stmt = $pdo->prepare('SELECT content FROM uploaded_files WHERE id = ? LIMIT 1');
$stmt->execute([$fileId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row || !isset($row['content'])) {
    http_response_code(404);
    exit('File not found');
}

$content = $row['content'];
if ($size <= 0) {
    $size = strlen($content);
}

header('Content-Type: ' . $mimeType);
header('Accept-Ranges: bytes');
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
