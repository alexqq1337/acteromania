<?php
/**
 * Aplică migrations/update_service4_features_single_line.sql
 *   php scripts/apply_service4_features_line.php
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

$stmts = [
    "UPDATE services SET features = '[\"căsătorie / divorț / deces pe actul de naștere sau căsătorie\", \"Eliberare duplicate\", \"Rectificare date\"]', updated_at = CURRENT_TIMESTAMP WHERE id = 4",
    "UPDATE services_translations SET features = '[\"căsătorie / divorț / deces pe actul de naștere sau căsătorie\", \"Eliberare duplicate\", \"Rectificare date\"]', updated_at = CURRENT_TIMESTAMP WHERE service_id = 4 AND language = 'ro'",
    "UPDATE services_translations SET features = '[\"Marriage / divorce / death on the birth or marriage certificate\", \"Duplicate issuance\", \"Data correction\"]', updated_at = CURRENT_TIMESTAMP WHERE service_id = 4 AND language = 'en'",
    "UPDATE services_translations SET features = '[\"Брак / развод / смерть в свидетельстве о рождении или браке\", \"Выдача дубликатов\", \"Исправление данных\"]', updated_at = CURRENT_TIMESTAMP WHERE service_id = 4 AND language = 'ru'",
];

foreach ($stmts as $sql) {
    $pdo->exec($sql);
}
echo "Features serviciu 4 actualizate.\n";
