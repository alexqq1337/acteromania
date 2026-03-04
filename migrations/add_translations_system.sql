-- =============================================
-- MIGRATION: Sistema de traduceri pentru conținut dinamic
-- ActeRomânia CMS - Professional Multilingual System
-- Limbi: ro (implicit), ru, en
-- =============================================

-- Dezactivează verificările de chei străine temporar
SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- 1. TABEL TRADUCERI SERVICII
-- =============================================
CREATE TABLE IF NOT EXISTS services_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    title VARCHAR(255) NOT NULL,
    description TEXT,
    short_description TEXT,
    full_description TEXT,
    features TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_service_lang (service_id, language),
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 2. TABEL TRADUCERI FAQ
-- =============================================
CREATE TABLE IF NOT EXISTS faq_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faq_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_faq_lang (faq_id, language),
    FOREIGN KEY (faq_id) REFERENCES faq(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 3. TABEL TRADUCERI HERO
-- =============================================
CREATE TABLE IF NOT EXISTS hero_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    title VARCHAR(255),
    subtitle TEXT,
    cta_text VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_hero_lang (hero_id, language),
    FOREIGN KEY (hero_id) REFERENCES hero(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 4. TABEL TRADUCERI HERO TRUST ITEMS
-- =============================================
CREATE TABLE IF NOT EXISTS hero_trust_items_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trust_item_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    text VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_trust_lang (trust_item_id, language),
    FOREIGN KEY (trust_item_id) REFERENCES hero_trust_items(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 5. TABEL TRADUCERI ABOUT
-- =============================================
CREATE TABLE IF NOT EXISTS about_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    about_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    section_label VARCHAR(100),
    title VARCHAR(255),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_about_lang (about_id, language),
    FOREIGN KEY (about_id) REFERENCES about(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 6. TABEL TRADUCERI ABOUT STATS
-- =============================================
CREATE TABLE IF NOT EXISTS about_stats_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stat_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    label VARCHAR(100) NOT NULL,
    suffix VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_stat_lang (stat_id, language),
    FOREIGN KEY (stat_id) REFERENCES about_stats(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 7. TABEL TRADUCERI SERVICES SECTION
-- =============================================
CREATE TABLE IF NOT EXISTS services_section_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_section_lang (section_id, language),
    FOREIGN KEY (section_id) REFERENCES services_section(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 8. TABEL TRADUCERI PROCESS SECTION
-- =============================================
CREATE TABLE IF NOT EXISTS process_section_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_section_lang (section_id, language),
    FOREIGN KEY (section_id) REFERENCES process_section(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 9. TABEL TRADUCERI PROCESS STEPS
-- =============================================
CREATE TABLE IF NOT EXISTS process_steps_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    step_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    title VARCHAR(255) NOT NULL,
    description TEXT,
    features TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_step_lang (step_id, language),
    FOREIGN KEY (step_id) REFERENCES process_steps(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 10. TABEL TRADUCERI WHY US
-- =============================================
CREATE TABLE IF NOT EXISTS why_us_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    why_us_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    section_label VARCHAR(100),
    title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_why_us_lang (why_us_id, language),
    FOREIGN KEY (why_us_id) REFERENCES why_us(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 11. TABEL TRADUCERI WHY US ITEMS
-- =============================================
CREATE TABLE IF NOT EXISTS why_us_items_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_item_lang (item_id, language),
    FOREIGN KEY (item_id) REFERENCES why_us_items(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 12. TABEL TRADUCERI FAQ SECTION
-- =============================================
CREATE TABLE IF NOT EXISTS faq_section_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    section_label VARCHAR(100),
    title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_section_lang (section_id, language),
    FOREIGN KEY (section_id) REFERENCES faq_section(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 13. TABEL TRADUCERI CONTACT
-- =============================================
CREATE TABLE IF NOT EXISTS contact_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NOT NULL,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    form_title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_contact_lang (contact_id, language),
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE,
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 14. TABEL TRADUCERI REVIEWS (opțional - recenziile pot fi în limba în care sunt scrise)
-- =============================================
-- Recenziile sunt de obicei în limba utilizatorului, dar putem adăuga traduceri pentru titlul secțiunii
CREATE TABLE IF NOT EXISTS reviews_section_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    language VARCHAR(5) NOT NULL DEFAULT 'ro',
    section_label VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_lang (language),
    INDEX idx_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Reactivează verificările de chei străine
-- =============================================
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- INSERARE DATE INIȚIALE (Dacă există date în tabelele principale)
-- =============================================

-- Inserează traduceri pentru servicii existente (copiază din tabelul principal)
INSERT IGNORE INTO services_translations (service_id, language, title, description, short_description, full_description, features)
SELECT id, 'ro', title, description, short_description, full_description, features FROM services;

-- Inserează traduceri pentru FAQ existente
INSERT IGNORE INTO faq_translations (faq_id, language, question, answer)
SELECT id, 'ro', question, answer FROM faq;

-- Inserează traduceri pentru Hero
INSERT IGNORE INTO hero_translations (hero_id, language, title, subtitle, cta_text)
SELECT id, 'ro', title, subtitle, cta_text FROM hero WHERE id = 1;

-- Inserează traduceri pentru About
INSERT IGNORE INTO about_translations (about_id, language, section_label, title, content)
SELECT id, 'ro', section_label, title, content FROM about WHERE id = 1;

-- Inserează traduceri pentru services_section
INSERT IGNORE INTO services_section_translations (section_id, language, section_label, title, description)
SELECT id, 'ro', section_label, title, description FROM services_section WHERE id = 1;

-- Inserează traduceri pentru process_section
INSERT IGNORE INTO process_section_translations (section_id, language, section_label, title, description)
SELECT id, 'ro', section_label, title, description FROM process_section WHERE id = 1;

-- Inserează traduceri pentru process_steps
INSERT IGNORE INTO process_steps_translations (step_id, language, title, description, features)
SELECT id, 'ro', title, description, features FROM process_steps;

-- Inserează traduceri pentru why_us
INSERT IGNORE INTO why_us_translations (why_us_id, language, section_label, title)
SELECT id, 'ro', section_label, title FROM why_us WHERE id = 1;

-- Inserează traduceri pentru why_us_items
INSERT IGNORE INTO why_us_items_translations (item_id, language, title, description)
SELECT id, 'ro', title, description FROM why_us_items;

-- Inserează traduceri pentru faq_section
INSERT IGNORE INTO faq_section_translations (section_id, language, section_label, title)
SELECT id, 'ro', section_label, title FROM faq_section WHERE id = 1;

-- Inserează traduceri pentru contact
INSERT IGNORE INTO contact_translations (contact_id, language, section_label, title, description, form_title)
SELECT id, 'ro', section_label, title, description, form_title FROM contact WHERE id = 1;

-- =============================================
-- VIEWS PENTRU QUERY-URI SIMPLIFICATE
-- =============================================

-- View pentru servicii cu traduceri
CREATE OR REPLACE VIEW v_services_translated AS
SELECT 
    s.id,
    s.image_url,
    s.icon_svg,
    s.sort_order,
    s.enabled,
    s.created_at,
    s.updated_at,
    COALESCE(st.title, s.title) AS title,
    COALESCE(st.description, s.description) AS description,
    COALESCE(st.short_description, s.short_description) AS short_description,
    COALESCE(st.full_description, s.full_description) AS full_description,
    COALESCE(st.features, s.features) AS features,
    COALESCE(st.language, 'ro') AS language
FROM services s
LEFT JOIN services_translations st ON s.id = st.service_id;

-- View pentru FAQ cu traduceri
CREATE OR REPLACE VIEW v_faq_translated AS
SELECT 
    f.id,
    f.sort_order,
    f.enabled,
    f.created_at,
    f.updated_at,
    COALESCE(ft.question, f.question) AS question,
    COALESCE(ft.answer, f.answer) AS answer,
    COALESCE(ft.language, 'ro') AS language
FROM faq f
LEFT JOIN faq_translations ft ON f.id = ft.faq_id;

-- =============================================
-- Mesaj de confirmare
-- =============================================
SELECT 'Migration completed successfully! Translation tables created.' AS status;
