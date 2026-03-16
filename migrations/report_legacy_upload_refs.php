<?php
/**
 * Reports DB rows that still reference uploads/* paths.
 *
 * Usage:
 *   php migrations/report_legacy_upload_refs.php
 */

require_once __DIR__ . '/../config.php';

$targets = [
    ['table' => 'hero', 'id' => 'id', 'columns' => ['image_url', 'video_url']],
    ['table' => 'about', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'services', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'process_steps', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'why_us', 'id' => 'id', 'columns' => ['image_url']],
    ['table' => 'media', 'id' => 'id', 'columns' => ['filepath']],
    ['table' => 'testimonials', 'id' => 'id', 'columns' => ['client_photo']],
];

$total = 0;
foreach ($targets as $target) {
    $table = $target['table'];
    $idCol = $target['id'];

    foreach ($target['columns'] as $column) {
        try {
            $stmt = $pdo->prepare('SELECT ' . $idCol . ' AS row_id, ' . $column . ' AS val FROM ' . $table . ' WHERE ' . $column . ' LIKE ?');
            $stmt->execute(['%uploads/%']);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            continue;
        }

        foreach ($rows as $row) {
            $total++;
            echo $table . '.' . $column . ' row ' . $row['row_id'] . ' => ' . $row['val'] . PHP_EOL;
        }
    }
}

echo 'Total legacy refs: ' . $total . PHP_EOL;
