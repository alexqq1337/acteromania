<?php
/**
 * Migrates legacy media references from uploads/* to DB-backed media-file.php?id=...
 *
 * Usage:
 *   php migrations/migrate_uploads_to_db.php
 */

require_once __DIR__ . '/../config.php';

ensureDbFileStorageTable();

$targets = [
    ['table' => 'hero', 'id' => 'id', 'columns' => ['image_url', 'video_url']],
    ['table' => 'about', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'services', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'process_steps', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'why_us', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'media', 'id' => 'id', 'columns' => ['filepath']],
    ['table' => 'testimonials', 'id' => 'id', 'columns' => ['client_photo']],
];

$sitePath = parse_url(SITE_URL, PHP_URL_PATH) ?: '';
$sitePath = rtrim($sitePath, '/');
$cacheByPath = [];
$summary = [
    'scanned' => 0,
    'updated' => 0,
    'skipped_missing' => 0,
    'skipped_non_upload' => 0,
    'skipped_too_large' => 0,
    'errors' => 0,
];

$maxPacket = getDbMaxAllowedPacket();

function isAlreadyDbMediaPath($value) {
    return extractDbFileId($value) > 0;
}

function normalizeLegacyUploadsPath($value, $sitePath) {
    $value = trim((string)$value);
    if ($value === '') {
        return null;
    }

    if (isAlreadyDbMediaPath($value)) {
        return null;
    }

    // Relative formats: uploads/x or /uploads/x
    if (strpos($value, 'uploads/') === 0 || strpos($value, '/uploads/') === 0) {
        return ltrim(str_replace('\\', '/', $value), '/');
    }

    // Absolute URL, but under this site uploads path.
    $parsed = parse_url($value);
    if (!empty($parsed['scheme']) && !empty($parsed['path'])) {
        $path = str_replace('\\', '/', $parsed['path']);

        if ($sitePath !== '' && strpos($path, $sitePath . '/uploads/') === 0) {
            return ltrim(substr($path, strlen($sitePath) + 1), '/');
        }

        $uploadsPos = strpos($path, '/uploads/');
        if ($uploadsPos !== false) {
            return ltrim(substr($path, $uploadsPos + 1), '/');
        }
    }

    return null;
}

function storeFileContentToDb($absolutePath, $originalName, $mimeType, $maxPacket) {
    global $pdo;

    $content = @file_get_contents($absolutePath);
    if ($content === false) {
        return false;
    }

    if ($maxPacket > 0 && strlen($content) >= (int)($maxPacket * 0.9)) {
        return 'TOO_LARGE';
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO uploaded_files (original_name, mime_type, file_size, content) VALUES (?, ?, ?, ?)');
        $stmt->bindValue(1, $originalName, PDO::PARAM_STR);
        $stmt->bindValue(2, $mimeType, PDO::PARAM_STR);
        $stmt->bindValue(3, (int)strlen($content), PDO::PARAM_INT);
        $stmt->bindValue(4, $content, PDO::PARAM_LOB);
        $stmt->execute();
    } catch (Throwable $e) {
        return false;
    }

    return DB_FILE_ENDPOINT . '?id=' . (int)$pdo->lastInsertId();
}

foreach ($targets as $target) {
    $table = $target['table'];
    $idCol = $target['id'];
    $columns = $target['columns'];

    $selectCols = array_merge([$idCol], $columns);
    $sql = 'SELECT ' . implode(', ', $selectCols) . ' FROM ' . $table;

    try {
        $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        // Skip tables missing in older schemas.
        continue;
    }

    foreach ($rows as $row) {
        $summary['scanned']++;
        $rowId = (int)$row[$idCol];
        $rowChanged = false;

        foreach ($columns as $column) {
            $currentValue = (string)($row[$column] ?? '');
            if ($currentValue === '' || isAlreadyDbMediaPath($currentValue)) {
                continue;
            }

            $relativePath = normalizeLegacyUploadsPath($currentValue, $sitePath);
            if ($relativePath === null) {
                $summary['skipped_non_upload']++;
                continue;
            }

            $absolutePath = __DIR__ . '/../' . $relativePath;
            if (!is_file($absolutePath)) {
                $summary['skipped_missing']++;
                continue;
            }

            if (isset($cacheByPath[$relativePath])) {
                $newPath = $cacheByPath[$relativePath];
            } else {
                $detectedMime = @mime_content_type($absolutePath);
                if (!$detectedMime) {
                    $detectedMime = 'application/octet-stream';
                }

                $newPath = storeFileContentToDb($absolutePath, basename($relativePath), $detectedMime, $maxPacket);
                if ($newPath === 'TOO_LARGE') {
                    $summary['skipped_too_large']++;
                    continue;
                }

                if ($newPath === false) {
                    $summary['errors']++;
                    continue;
                }

                $cacheByPath[$relativePath] = $newPath;
            }

            try {
                $update = $pdo->prepare('UPDATE ' . $table . ' SET ' . $column . ' = ? WHERE ' . $idCol . ' = ?');
                $update->execute([$newPath, $rowId]);
                $rowChanged = true;
            } catch (Throwable $e) {
                $summary['errors']++;
            }
        }

        if ($rowChanged) {
            $summary['updated']++;
        }
    }
}

echo "Migration finished\n";
echo 'Rows scanned: ' . $summary['scanned'] . "\n";
echo 'Rows updated: ' . $summary['updated'] . "\n";
echo 'Skipped (non-uploads refs): ' . $summary['skipped_non_upload'] . "\n";
echo 'Skipped (missing files): ' . $summary['skipped_missing'] . "\n";
echo 'Skipped (too large for DB packet): ' . $summary['skipped_too_large'] . "\n";
echo 'Errors: ' . $summary['errors'] . "\n";
