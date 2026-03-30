<?php
/**
 * Aplică titlurile RU pentru servicii în MySQL (tabla services_translations).
 * Fișierul SQL din migrations nu rulează singur — trebuie aplicat pe baza ta.
 *
 *   php scripts/apply_russian_service_titles.php
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo 'Rulează din terminal: php scripts/apply_russian_service_titles.php';
    exit;
}

$root = dirname(__DIR__);
require_once $root . '/config.php';

$updates = [
    [5, 'Румынское гражданство через суд'],
    [2, 'Румынский паспорт'],
    [3, 'Румынский булетин'],
    [13, 'Румынские водительские права'],
    [8, 'Апостиль документов'],
    [10, 'Услуги для диаспоры'],
    [15, 'Сертификаты квалификации - CIP'],
    [14, 'Пособие на ребёнка'],
    [11, 'Акты гражданского состояния'],
    [9, 'Справка о несудимости'],
    [17, 'Номер дела'],
    [7, 'Перевод документов'],
    [6, 'Запись на присягу'],
    [4, 'Румынские свидетельства о рождении и браке'],
    [12, 'Доставка румынских документов'],
    [16, 'Транспорт в Румынию и обратно'],
];

$pdo = getDB();
$pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

$stmt = $pdo->prepare(
    'UPDATE services_translations SET title = ?, updated_at = CURRENT_TIMESTAMP WHERE service_id = ? AND language = ?'
);

$n = 0;
foreach ($updates as [$id, $title]) {
    $stmt->execute([$title, $id, 'ru']);
    $n += $stmt->rowCount();
}

echo "Actualizat {$n} rând(uri) în services_translations (language=ru).\n";
echo "Reîncarcă site-ul cu Ctrl+F5 (limba rusă).\n";
