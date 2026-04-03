<?php
/**
 * Sincronizează limba implicită (DEFAULT_LANGUAGE, de obicei ro) în tabelele *_translations
 * după ce panoul salvează în tabelele principale. Site-ul folosește COALESCE(traducere, bază),
 * deci traducerile vechi pentru ro blocau modificările din CMS.
 */

if (!defined('DEFAULT_LANGUAGE')) {
    return;
}

function sync_hero_default_lang(PDO $pdo, int $heroId, string $title, string $subtitle, string $ctaText): void {
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO hero_translations (hero_id, language, title, subtitle, cta_text, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE title = VALUES(title), subtitle = VALUES(subtitle), cta_text = VALUES(cta_text), updated_at = NOW()';
    $pdo->prepare($sql)->execute([$heroId, $lang, $title, $subtitle, $ctaText]);
}

function sync_hero_trust_item_default_lang(PDO $pdo, int $itemId, string $text): void {
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO hero_trust_items_translations (trust_item_id, language, text, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE text = VALUES(text), updated_at = NOW()';
    $pdo->prepare($sql)->execute([$itemId, $lang, $text]);
}

function sync_about_default_lang(PDO $pdo, int $aboutId, string $sectionLabel, string $title, string $content): void {
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO about_translations (about_id, language, section_label, title, content, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE section_label = VALUES(section_label), title = VALUES(title), content = VALUES(content), updated_at = NOW()';
    $pdo->prepare($sql)->execute([$aboutId, $lang, $sectionLabel, $title, $content]);
}

function sync_about_stat_default_lang(PDO $pdo, int $statId, string $label, string $suffix): void {
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO about_stats_translations (stat_id, language, label, suffix, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE label = VALUES(label), suffix = VALUES(suffix), updated_at = NOW()';
    $pdo->prepare($sql)->execute([$statId, $lang, $label, $suffix]);
}

function sync_services_section_from_db(PDO $pdo, int $sectionId = 1): void {
    $stmt = $pdo->prepare('SELECT * FROM services_section WHERE id = ? LIMIT 1');
    $stmt->execute([$sectionId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO services_section_translations (section_id, language, section_label, title, description, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE section_label = VALUES(section_label), title = VALUES(title), description = VALUES(description), updated_at = NOW()';
    $pdo->prepare($sql)->execute([
        $sectionId,
        $lang,
        (string) ($row['section_label'] ?? ''),
        (string) ($row['title'] ?? ''),
        (string) ($row['description'] ?? ''),
    ]);
}

function sync_service_from_base(PDO $pdo, int $serviceId): void {
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([$serviceId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    saveServiceTranslation($pdo, $serviceId, DEFAULT_LANGUAGE, [
        'title' => $row['title'] ?? '',
        'description' => $row['description'] ?? '',
        'short_description' => $row['short_description'] ?? '',
        'full_description' => $row['full_description'] ?? '',
        'features' => $row['features'] ?? '',
    ]);
}

function sync_process_section_from_db(PDO $pdo, int $sectionId = 1): void {
    $stmt = $pdo->prepare('SELECT * FROM process_section WHERE id = ? LIMIT 1');
    $stmt->execute([$sectionId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO process_section_translations (section_id, language, section_label, title, description, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE section_label = VALUES(section_label), title = VALUES(title), description = VALUES(description), updated_at = NOW()';
    $pdo->prepare($sql)->execute([
        $sectionId,
        $lang,
        (string) ($row['section_label'] ?? ''),
        (string) ($row['title'] ?? ''),
        (string) ($row['description'] ?? ''),
    ]);
}

function sync_process_step_from_db(PDO $pdo, int $stepId): void {
    $stmt = $pdo->prepare('SELECT * FROM process_steps WHERE id = ?');
    $stmt->execute([$stepId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO process_steps_translations (step_id, language, title, description, features, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), features = VALUES(features), updated_at = NOW()';
    $pdo->prepare($sql)->execute([
        $stepId,
        $lang,
        (string) ($row['title'] ?? ''),
        (string) ($row['description'] ?? ''),
        (string) ($row['features'] ?? ''),
    ]);
}

function sync_why_us_from_db(PDO $pdo, int $id = 1): void {
    $stmt = $pdo->prepare('SELECT * FROM why_us WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO why_us_translations (why_us_id, language, section_label, title, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE section_label = VALUES(section_label), title = VALUES(title), updated_at = NOW()';
    $pdo->prepare($sql)->execute([
        $id,
        $lang,
        (string) ($row['section_label'] ?? ''),
        (string) ($row['title'] ?? ''),
    ]);
}

function sync_why_us_item_from_db(PDO $pdo, int $itemId): void {
    $stmt = $pdo->prepare('SELECT * FROM why_us_items WHERE id = ?');
    $stmt->execute([$itemId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO why_us_items_translations (item_id, language, title, description, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), updated_at = NOW()';
    $pdo->prepare($sql)->execute([
        $itemId,
        $lang,
        (string) ($row['title'] ?? ''),
        (string) ($row['description'] ?? ''),
    ]);
}

function sync_faq_section_from_db(PDO $pdo, int $sectionId = 1): void {
    $stmt = $pdo->prepare('SELECT * FROM faq_section WHERE id = ?');
    $stmt->execute([$sectionId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO faq_section_translations (section_id, language, section_label, title, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE section_label = VALUES(section_label), title = VALUES(title), updated_at = NOW()';
    $pdo->prepare($sql)->execute([
        $sectionId,
        $lang,
        (string) ($row['section_label'] ?? ''),
        (string) ($row['title'] ?? ''),
    ]);
}

function sync_faq_from_base(PDO $pdo, int $faqId): void {
    $stmt = $pdo->prepare('SELECT * FROM faq WHERE id = ?');
    $stmt->execute([$faqId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return;
    }
    saveFaqTranslation($pdo, $faqId, DEFAULT_LANGUAGE, [
        'question' => (string) ($row['question'] ?? ''),
        'answer' => (string) ($row['answer'] ?? ''),
    ]);
}

function sync_contact_from_db(PDO $pdo, int $contactId = 1): void {
    $stmt = $pdo->prepare('SELECT * FROM contact WHERE id = ?');
    $stmt->execute([$contactId]);
    $c = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$c) {
        return;
    }
    $lang = DEFAULT_LANGUAGE;
    $sql = 'INSERT INTO contact_translations (contact_id, language, section_label, title, description, form_title, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE section_label = VALUES(section_label), title = VALUES(title), description = VALUES(description), form_title = VALUES(form_title), updated_at = NOW()';
    $pdo->prepare($sql)->execute([
        $contactId,
        $lang,
        (string) ($c['section_label'] ?? ''),
        (string) ($c['title'] ?? ''),
        (string) ($c['description'] ?? ''),
        (string) ($c['form_title'] ?? ''),
    ]);
}
