<?php
/**
 * ActeRomânia CMS - Configuration File
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'acteromania_cms');
define('DB_USER', 'acteromania');
define('DB_PASS', 'acteromania');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration — URL dinamic; https dacă cererea sau proxy-ul o indică
$siteHost = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : 'localhost:8080';
$siteHttps = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
define('SITE_URL', ($siteHttps ? 'https://' : 'http://') . $siteHost);
define('ADMIN_URL', SITE_URL . '/control-panel-cetateniero');
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('DB_FILE_ENDPOINT', 'media-file.php');

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_NAME', 'acteromania_session');

/**
 * HTTPS văzut de PHP (inclusiv după proxy / X-Forwarded-Proto)
 */
function isHttpsRequest(): bool {
    return (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
}

// Admin credentials
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$10$Mxes5YuGpziiSzfpRyfNtuiOY5MIhcrc0cwfp9qygtaqM5vKW4BXe');

// Start session — cookie compatibil HTTPS + SameSite (evită probleme după schimbare limbă pe domeniu)
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    $secure = isHttpsRequest();
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    } else {
        session_set_cookie_params(0, '/', '', $secure, true);
    }
    session_start();
}

// Include Database Translation Helper (for JOIN queries)
if (file_exists(__DIR__ . '/config/db_translations.php')) {
    require_once __DIR__ . '/config/db_translations.php';
}
// Database connection
function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

// Initialize global PDO connection
$pdo = getDB();

// Security functions
function generateCSRFToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function verifyCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// HTML escape helper used in templates.
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_URL . '/index.php');
        exit;
    }
}

function ensureDbFileStorageTable() {
    static $initialized = false;
    global $pdo;

    if ($initialized) {
        return;
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS uploaded_files (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        original_name VARCHAR(255) NOT NULL,
        mime_type VARCHAR(100) NOT NULL,
        file_size INT UNSIGNED NOT NULL,
        content LONGBLOB NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $initialized = true;
}

function getDbMaxAllowedPacket() {
    static $value = null;
    global $pdo;

    if ($value !== null) {
        return $value;
    }

    try {
        $value = (int)$pdo->query("SELECT @@max_allowed_packet")->fetchColumn();
    } catch (Throwable $e) {
        $value = 0;
    }

    return $value;
}

function storeUploadedFileInDb($file, array $allowedTypes, $maxSize) {
    global $pdo;

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return false;
    }

    if (($file['size'] ?? 0) > $maxSize) {
        return false;
    }

    $mimeType = (string)($file['type'] ?? 'application/octet-stream');
    if (!in_array($mimeType, $allowedTypes, true)) {
        return false;
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        return false;
    }

    $content = file_get_contents($file['tmp_name']);
    if ($content === false) {
        return false;
    }

    $dbPacketLimit = getDbMaxAllowedPacket();
    if ($dbPacketLimit > 0 && strlen($content) >= (int)($dbPacketLimit * 0.9)) {
        return false;
    }

    ensureDbFileStorageTable();

    $stmt = $pdo->prepare("INSERT INTO uploaded_files (original_name, mime_type, file_size, content) VALUES (?, ?, ?, ?)");
    $stmt->bindValue(1, (string)($file['name'] ?? 'upload.bin'), PDO::PARAM_STR);
    $stmt->bindValue(2, $mimeType, PDO::PARAM_STR);
    $stmt->bindValue(3, (int)strlen($content), PDO::PARAM_INT);
    $stmt->bindValue(4, $content, PDO::PARAM_LOB);
    $stmt->execute();

    return DB_FILE_ENDPOINT . '?id=' . (int)$pdo->lastInsertId();
}

function extractDbFileId($path) {
    $value = trim((string)$path);
    if ($value === '') {
        return 0;
    }

    $parsed = parse_url($value);
    if ($parsed === false) {
        return 0;
    }

    $scriptPath = $parsed['path'] ?? $value;
    if (basename($scriptPath) !== DB_FILE_ENDPOINT) {
        return 0;
    }

    $query = $parsed['query'] ?? '';
    parse_str($query, $params);
    return isset($params['id']) ? (int)$params['id'] : 0;
}

// File upload helper
function uploadImage($file, $folder = '') {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    return storeUploadedFileInDb($file, $allowedTypes, $maxSize);
}

// Video upload helper
function uploadVideo($file, $folder = '') {
    $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
    $maxSize = 50 * 1024 * 1024; // 50MB

    return storeUploadedFileInDb($file, $allowedTypes, $maxSize);
}

function deleteImage($filename, $folder = '') {
    global $pdo;

    $dbFileId = extractDbFileId($filename);
    if ($dbFileId > 0) {
        ensureDbFileStorageTable();
        $stmt = $pdo->prepare("DELETE FROM uploaded_files WHERE id = ?");
        return $stmt->execute([$dbFileId]);
    }

    $normalized = ltrim(str_replace('\\', '/', (string)$filename), '/');
    $legacyPath = __DIR__ . '/' . $normalized;

    if (!file_exists($legacyPath)) {
        $legacyPath = UPLOAD_PATH . ($folder ? $folder . '/' : '') . basename($normalized);
    }

    if (file_exists($legacyPath)) {
        return unlink($legacyPath);
    }

    return false;
}

// =============================================
// LANGUAGE / TRANSLATION SYSTEM
// =============================================
define('AVAILABLE_LANGUAGES', ['ro', 'ru', 'en']);
define('DEFAULT_LANGUAGE', 'ro');

// Sincronizare limba implicită (ro) panou → *_translations (după DEFAULT_LANGUAGE și db_translations)
if (file_exists(__DIR__ . '/config/sync_default_lang.php')) {
    require_once __DIR__ . '/config/sync_default_lang.php';
}

/**
 * Persistă limba în sesiune + cookie (folosit la schimbare limbă din UI).
 */
function setLanguagePreference(string $lang): void {
    if (!in_array($lang, AVAILABLE_LANGUAGES, true)) {
        return;
    }
    $_SESSION['language'] = $lang;
    $exp = time() + (365 * 24 * 60 * 60);
    if (PHP_VERSION_ID >= 70300) {
        setcookie('language', $lang, [
            'expires' => $exp,
            'path' => '/',
            'secure' => isHttpsRequest(),
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
    } else {
        setcookie('language', $lang, $exp, '/', '', isHttpsRequest(), false);
    }
}

/**
 * Get current language from session/cookie
 * (?lang= e tratat în index.php prin redirect PRG — nu mai încarcă tot HTML-ul în același request ca setarea cookie)
 */
function getCurrentLanguage() {
    // Check session
    if (isset($_SESSION['language']) && in_array($_SESSION['language'], AVAILABLE_LANGUAGES)) {
        return $_SESSION['language'];
    }
    
    // Check cookie
    if (isset($_COOKIE['language']) && in_array($_COOKIE['language'], AVAILABLE_LANGUAGES)) {
        $_SESSION['language'] = $_COOKIE['language'];
        return $_COOKIE['language'];
    }
    
    // Default
    $_SESSION['language'] = DEFAULT_LANGUAGE;
    return DEFAULT_LANGUAGE;
}

/**
 * Load translations for current language
 */
function loadTranslations($lang = null) {
    static $translations = null;
    static $loadedLang = null;
    
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    if ($translations !== null && $loadedLang === $lang) {
        return $translations;
    }
    
    $langFile = __DIR__ . '/lang/' . $lang . '.php';
    
    if (file_exists($langFile)) {
        $translations = include $langFile;
        $loadedLang = $lang;
    } else {
        // Fallback to Romanian
        $translations = include __DIR__ . '/lang/ro.php';
        $loadedLang = 'ro';
    }
    
    return $translations;
}

/**
 * Get translation by key
 */
function __($key, $default = null) {
    $translations = loadTranslations();
    return $translations[$key] ?? ($default !== null ? $default : $key);
}

/**
 * Echo translation by key
 */
function _e($key, $default = null) {
    echo __($key, $default);
}

/**
 * Get language name by code
 */
function getLanguageName($code) {
    $names = [
        'ro' => 'Română',
        'ru' => 'Русский',
        'en' => 'English'
    ];
    return $names[$code] ?? $code;
}

/**
 * Get language flag emoji by code
 */
function getLanguageFlag($code) {
    $flags = [
        'ro' => '🇷🇴',
        'ru' => '🇷🇺',
        'en' => '🇬🇧'
    ];
    return $flags[$code] ?? '';
}

/**
 * Get translated field value from database record
 * Works with both:
 * - JOINed translation data (from getXXXTranslated functions) - translation is in the field itself
 * - Legacy suffix format (title_ru, title_en)
 * 
 * @param array $row Database row
 * @param string $field Base field name (e.g., 'title')
 * @param string|null $lang Language code (uses current if null)
 * @return string Translated value or default
 */
function getTranslated($row, $field, $lang = null) {
    if ($lang === null) {
        $lang = getCurrentLanguage();
    }
    
    // If Romanian or default language, return base field
    if ($lang === 'ro' || $lang === DEFAULT_LANGUAGE) {
        return $row[$field] ?? '';
    }

    // First try: legacy suffix format (e.g., title_ru, title_en).
    // This is required for admin-managed translations stored directly in base tables.
    $translatedField = $field . '_' . $lang;
    if (!empty($row[$translatedField])) {
        return $row[$translatedField];
    }

    // Second try/fallback: the field itself.
    // For JOIN-based translation queries from db_translations.php, COALESCE already places
    // the translated value directly in the base field alias.
    return $row[$field] ?? '';
}

/**
 * Shorthand for getTranslated with echo
 */
function t($row, $field, $lang = null) {
    return getTranslated($row, $field, $lang);
}
?>
