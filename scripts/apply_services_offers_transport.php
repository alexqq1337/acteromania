<?php
/**
 * Adaugă coloana services.offers_transport dacă lipsește.
 *   php scripts/apply_services_offers_transport.php
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo 'CLI only';
    exit;
}

require_once dirname(__DIR__) . '/config.php';

$pdo = getDB();
$pdo->exec('SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci');

$stmt = $pdo->query("
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'services' AND COLUMN_NAME = 'offers_transport'
");
if ((int) $stmt->fetchColumn() > 0) {
    echo "Coloana offers_transport există deja.\n";
    exit(0);
}

$pdo->exec('ALTER TABLE `services` ADD COLUMN `offers_transport` tinyint(1) NOT NULL DEFAULT 0 AFTER `enabled`');
echo "Coloana offers_transport adăugată.\n";
