<?php
/**
 * ActeRomânia CMS - Database Translation Helper
 * Funcții pentru obținerea conținutului tradus din baza de date
 */

/**
 * Obține serviciile cu traduceri pentru limba curentă
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @param bool $enabledOnly Doar servicii active
 * @return array Lista de servicii traduse
 */
function getServicesTranslated(PDO $pdo, ?string $lang = null, bool $enabledOnly = true): array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            s.id,
            s.image_url,
            s.icon_svg,
            s.sort_order,
            s.enabled,
            COALESCE(st.title, s.title) AS title,
            COALESCE(st.description, s.description) AS description,
            COALESCE(st.short_description, s.short_description) AS short_description,
            COALESCE(st.full_description, s.full_description) AS full_description,
            COALESCE(st.features, s.features) AS features
        FROM services s
        LEFT JOIN services_translations st ON s.id = st.service_id AND st.language = ?
    ";
    
    if ($enabledOnly) {
        $sql .= " WHERE s.enabled = 1";
    }
    
    $sql .= " ORDER BY s.sort_order ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obține un singur serviciu cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param int $serviceId ID-ul serviciului
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Serviciul tradus sau null
 */
function getServiceTranslated(PDO $pdo, int $serviceId, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            s.id,
            s.image_url,
            s.icon_svg,
            s.sort_order,
            s.enabled,
            COALESCE(st.title, s.title) AS title,
            COALESCE(st.description, s.description) AS description,
            COALESCE(st.short_description, s.short_description) AS short_description,
            COALESCE(st.full_description, s.full_description) AS full_description,
            COALESCE(st.features, s.features) AS features
        FROM services s
        LEFT JOIN services_translations st ON s.id = st.service_id AND st.language = ?
        WHERE s.id = ?
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang, $serviceId]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține FAQ cu traduceri pentru limba curentă
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @param bool $enabledOnly Doar FAQ active
 * @return array Lista de FAQ traduse
 */
function getFaqTranslated(PDO $pdo, ?string $lang = null, bool $enabledOnly = true): array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            f.id,
            f.sort_order,
            f.enabled,
            COALESCE(ft.question, f.question) AS question,
            COALESCE(ft.answer, f.answer) AS answer
        FROM faq f
        LEFT JOIN faq_translations ft ON f.id = ft.faq_id AND ft.language = ?
    ";
    
    if ($enabledOnly) {
        $sql .= " WHERE f.enabled = 1";
    }
    
    $sql .= " ORDER BY f.sort_order ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obține secțiunea Hero cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Datele Hero traduse
 */
function getHeroTranslated(PDO $pdo, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            h.*,
            COALESCE(ht.title, h.title) AS title,
            COALESCE(ht.subtitle, h.subtitle) AS subtitle,
            COALESCE(ht.cta_text, h.cta_text) AS cta_text
        FROM hero h
        LEFT JOIN hero_translations ht ON h.id = ht.hero_id AND ht.language = ?
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține secțiunea About cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Datele About traduse
 */
function getAboutTranslated(PDO $pdo, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            a.*,
            COALESCE(at.section_label, a.section_label) AS section_label,
            COALESCE(at.title, a.title) AS title,
            COALESCE(at.content, a.content) AS content
        FROM about a
        LEFT JOIN about_translations at ON a.id = at.about_id AND at.language = ?
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține secțiunea Services cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Datele secțiunii traduse
 */
function getServicesSectionTranslated(PDO $pdo, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            s.*,
            COALESCE(st.section_label, s.section_label) AS section_label,
            COALESCE(st.title, s.title) AS title,
            COALESCE(st.description, s.description) AS description
        FROM services_section s
        LEFT JOIN services_section_translations st ON s.id = st.section_id AND st.language = ?
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține secțiunea Process cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Datele secțiunii traduse
 */
function getProcessSectionTranslated(PDO $pdo, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            p.*,
            COALESCE(pt.section_label, p.section_label) AS section_label,
            COALESCE(pt.title, p.title) AS title,
            COALESCE(pt.description, p.description) AS description
        FROM process_section p
        LEFT JOIN process_section_translations pt ON p.id = pt.section_id AND pt.language = ?
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține pașii procesului cu traduceri
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @param bool $enabledOnly Doar pași activi
 * @return array Lista de pași traduși
 */
function getProcessStepsTranslated(PDO $pdo, ?string $lang = null, bool $enabledOnly = true): array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            p.id,
            p.step_number,
            p.image_url,
            p.sort_order,
            p.enabled,
            COALESCE(pt.title, p.title) AS title,
            COALESCE(pt.description, p.description) AS description,
            COALESCE(pt.features, p.features) AS features
        FROM process_steps p
        LEFT JOIN process_steps_translations pt ON p.id = pt.step_id AND pt.language = ?
    ";
    
    if ($enabledOnly) {
        $sql .= " WHERE p.enabled = 1";
    }
    
    $sql .= " ORDER BY p.sort_order ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obține secțiunea Why Us cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Datele secțiunii traduse
 */
function getWhyUsTranslated(PDO $pdo, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            w.*,
            COALESCE(wt.section_label, w.section_label) AS section_label,
            COALESCE(wt.title, w.title) AS title
        FROM why_us w
        LEFT JOIN why_us_translations wt ON w.id = wt.why_us_id AND wt.language = ?
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține itemii Why Us cu traduceri
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @param bool $enabledOnly Doar itemi activi
 * @return array Lista de itemi traduși
 */
function getWhyUsItemsTranslated(PDO $pdo, ?string $lang = null, bool $enabledOnly = true): array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            w.id,
            w.icon_svg,
            w.sort_order,
            w.enabled,
            COALESCE(wt.title, w.title) AS title,
            COALESCE(wt.description, w.description) AS description
        FROM why_us_items w
        LEFT JOIN why_us_items_translations wt ON w.id = wt.item_id AND wt.language = ?
    ";
    
    if ($enabledOnly) {
        $sql .= " WHERE w.enabled = 1";
    }
    
    $sql .= " ORDER BY w.sort_order ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obține secțiunea FAQ cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Datele secțiunii traduse
 */
function getFaqSectionTranslated(PDO $pdo, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            f.*,
            COALESCE(ft.section_label, f.section_label) AS section_label,
            COALESCE(ft.title, f.title) AS title
        FROM faq_section f
        LEFT JOIN faq_section_translations ft ON f.id = ft.section_id AND ft.language = ?
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține secțiunea Contact cu traducere
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @return array|null Datele secțiunii traduse
 */
function getContactTranslated(PDO $pdo, ?string $lang = null): ?array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            c.*,
            COALESCE(ct.section_label, c.section_label) AS section_label,
            COALESCE(ct.title, c.title) AS title,
            COALESCE(ct.description, c.description) AS description,
            COALESCE(ct.form_title, c.form_title) AS form_title
        FROM contact c
        LEFT JOIN contact_translations ct ON c.id = ct.contact_id AND ct.language = ?
        WHERE c.id = 1
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Obține statisticile About cu traduceri
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @param bool $enabledOnly Doar statistici active
 * @return array Lista de statistici traduse
 */
function getAboutStatsTranslated(PDO $pdo, ?string $lang = null, bool $enabledOnly = true): array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            a.id,
            a.icon_svg,
            a.number_value,
            a.sort_order,
            a.enabled,
            COALESCE(at.label, a.label) AS label,
            COALESCE(at.suffix, a.suffix) AS suffix
        FROM about_stats a
        LEFT JOIN about_stats_translations at ON a.id = at.stat_id AND at.language = ?
    ";
    
    if ($enabledOnly) {
        $sql .= " WHERE a.enabled = 1";
    }
    
    $sql .= " ORDER BY a.sort_order ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obține itemii Hero Trust cu traduceri
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param string|null $lang Limba (null = limba curentă)
 * @param bool $enabledOnly Doar itemi activi
 * @return array Lista de itemi traduși
 */
function getHeroTrustItemsTranslated(PDO $pdo, ?string $lang = null, bool $enabledOnly = true): array {
    $lang = $lang ?? getCurrentLanguage();
    
    $sql = "
        SELECT 
            h.id,
            h.icon_svg,
            h.sort_order,
            h.enabled,
            COALESCE(ht.text, h.text) AS text
        FROM hero_trust_items h
        LEFT JOIN hero_trust_items_translations ht ON h.id = ht.trust_item_id AND ht.language = ?
    ";
    
    if ($enabledOnly) {
        $sql .= " WHERE h.enabled = 1";
    }
    
    $sql .= " ORDER BY h.sort_order ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Salvează o traducere pentru un serviciu
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param int $serviceId ID-ul serviciului
 * @param string $lang Limba
 * @param array $data Datele traducerii (title, description, etc.)
 * @return bool Succes
 */
function saveServiceTranslation(PDO $pdo, int $serviceId, string $lang, array $data): bool {
    $sql = "
        INSERT INTO services_translations 
            (service_id, language, title, description, short_description, full_description, features)
        VALUES 
            (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            title = VALUES(title),
            description = VALUES(description),
            short_description = VALUES(short_description),
            full_description = VALUES(full_description),
            features = VALUES(features),
            updated_at = NOW()
    ";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $serviceId,
        $lang,
        $data['title'] ?? '',
        $data['description'] ?? '',
        $data['short_description'] ?? '',
        $data['full_description'] ?? '',
        $data['features'] ?? ''
    ]);
}

/**
 * Salvează o traducere pentru un FAQ
 * 
 * @param PDO $pdo Conexiunea la baza de date
 * @param int $faqId ID-ul FAQ
 * @param string $lang Limba
 * @param array $data Datele traducerii (question, answer)
 * @return bool Succes
 */
function saveFaqTranslation(PDO $pdo, int $faqId, string $lang, array $data): bool {
    $sql = "
        INSERT INTO faq_translations 
            (faq_id, language, question, answer)
        VALUES 
            (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            question = VALUES(question),
            answer = VALUES(answer),
            updated_at = NOW()
    ";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $faqId,
        $lang,
        $data['question'] ?? '',
        $data['answer'] ?? ''
    ]);
}
