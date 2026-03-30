-- ==================================================
-- SISTEM DE RECENZII - Migrare Baza de Date
-- Rulează acest script în phpMyAdmin sau MySQL CLI
-- ==================================================

-- Tabelul principal pentru recenzii
CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Datele recenziei
    name VARCHAR(80) NOT NULL COMMENT 'Numele utilizatorului',
    email VARCHAR(120) NOT NULL COMMENT 'Adresa de email',
    title VARCHAR(100) NOT NULL COMMENT 'Titlul recenziei',
    message TEXT NOT NULL COMMENT 'Textul recenziei',
    rating TINYINT UNSIGNED NOT NULL COMMENT 'Rating 1-5 stele',
    
    -- Status și moderare
    approved TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=în așteptare, 1=aprobat, 2=respins',
    
    -- Metadate pentru securitate
    ip_address VARCHAR(45) DEFAULT NULL COMMENT 'IP-ul utilizatorului',
    user_agent VARCHAR(255) DEFAULT NULL COMMENT 'Browser-ul utilizatorului',
    
    -- Timestamp-uri
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data trimiterii',
    approved_at TIMESTAMP NULL DEFAULT NULL COMMENT 'Data aprobării',
    
    -- Indexuri pentru performanță
    INDEX idx_reviews_approved (approved),
    INDEX idx_reviews_created_at (created_at),
    INDEX idx_reviews_email (email)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==================================================
-- COMENZI UTILE PENTRU ADMINISTRARE
-- ==================================================

-- Vizualizare recenzii în așteptare:
-- SELECT * FROM reviews WHERE approved = 0 ORDER BY created_at DESC;

-- Aprobare recenzie:
-- UPDATE reviews SET approved = 1, approved_at = NOW() WHERE id = ?;

-- Respingere recenzie:
-- UPDATE reviews SET approved = 2 WHERE id = ?;

-- Ștergere recenzie:
-- DELETE FROM reviews WHERE id = ?;

-- Vizualizare toate recenziile aprobate:
-- SELECT * FROM reviews WHERE approved = 1 ORDER BY created_at DESC;
