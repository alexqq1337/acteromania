<?php
/**
 * Aplică migrarea sync_hero_ro_with_ru_messaging.sql (hero RO aliniat cu RU/EN).
 *   php scripts/apply_hero_ro_alignment.php
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

$statements = [
    "UPDATE hero SET title = 'Cetățenie română - Serviciu complet', subtitle = 'Asistență juridică rapidă și profesională pentru obținerea cetățeniei române, a pașaportului și a cărții de identitate. Peste 15.000 de cazuri reușite.', cta_text = 'Consultație gratuită', updated_at = CURRENT_TIMESTAMP WHERE id = 1",
    "UPDATE hero_translations SET title = 'Cetățenie română - Serviciu complet', subtitle = 'Asistență juridică rapidă și profesională pentru obținerea cetățeniei române, a pașaportului și a cărții de identitate. Peste 15.000 de cazuri reușite.', cta_text = 'Consultație gratuită', updated_at = CURRENT_TIMESTAMP WHERE hero_id = 1 AND language = 'ro'",
    "UPDATE hero_trust_items SET text = '15+ ani de experiență', updated_at = CURRENT_TIMESTAMP WHERE id = 1",
    "UPDATE hero_trust_items SET text = '15.000+ cazuri reușite', updated_at = CURRENT_TIMESTAMP WHERE id = 2",
    "UPDATE hero_trust_items SET text = 'Termene scurte de procesare', updated_at = CURRENT_TIMESTAMP WHERE id = 3",
];

foreach ($statements as $sql) {
    $pdo->exec($sql);
}

echo "Hero RO + trust bar actualizate (alinieri cu mesajul rus/eng).\n";
