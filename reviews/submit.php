<?php
/**
 * ==================================================
 * SISTEM DE RECENZII - Backend pentru procesare
 * ==================================================
 * 
 * Acest fișier procesează trimiterile de recenzii via AJAX
 * Validează datele și le salvează în baza de date
 * 
 * Endpoint: POST /reviews/submit.php
 * Content-Type: application/json
 */

// Încarcă configurația și funcțiile helper
require_once __DIR__ . '/../config.php';

// Setează header-ul pentru răspuns JSON
header('Content-Type: application/json; charset=utf-8');

// Permite doar metoda POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodă nepermisă']);
    exit;
}

// ===== CITIRE DATE DIN REQUEST =====
$input = json_decode(file_get_contents('php://input'), true);

// Dacă nu e JSON valid, încearcă POST normal
if (!$input) {
    $input = $_POST;
}

// ===== EXTRAGERE ȘI SANITIZARE DATE =====
$name = trim(strip_tags($input['name'] ?? ''));
$title = trim(strip_tags($input['title'] ?? ''));
$message = trim(strip_tags($input['message'] ?? ''));
$rating = (int)($input['rating'] ?? 0);
$terms = (bool)($input['terms'] ?? false);
$csrfToken = $input['csrf_token'] ?? '';
$honeypot = trim($input['website'] ?? ''); // Câmp honeypot anti-spam
$formLoadTime = (int)($input['_ft'] ?? 0); // Timp încărcare formular (anti-bot)

// ===== VALIDARE CSRF TOKEN =====
if (!verifyCSRFToken($csrfToken)) {
    http_response_code(403);
    echo json_encode([
        'success' => false, 
        'error' => 'Sesiunea a expirat. Vă rugăm reîncărcați pagina.',
        'field' => 'csrf'
    ]);
    exit;
}

// ===== VERIFICARE HONEYPOT (ANTI-SPAM) =====
// Dacă câmpul ascuns "website" este completat, e probabil spam
if ($honeypot !== '') {
    // Răspundem cu succes fals pentru a păcăli botul
    echo json_encode([
        'success' => true, 
        'message' => 'Recenzia a fost trimisă cu succes!'
    ]);
    exit;
}

// ===== RATE LIMITING (MAX 1 RECENZIE PE MINUT) =====
$lastReviewAt = $_SESSION['last_review_at'] ?? 0;
if ($lastReviewAt && (time() - $lastReviewAt) < 60) {
    $waitTime = 60 - (time() - $lastReviewAt);
    http_response_code(429);
    echo json_encode([
        'success' => false, 
        'error' => "Vă rugăm așteptați {$waitTime} secunde înainte de a trimite o nouă recenzie.",
        'field' => 'rate_limit'
    ]);
    exit;
}

// ===== ANTI-SPAM: VERIFICARE TIMP FORMULAR =====
// Boturile trimit formularul foarte rapid (< 3 secunde)
if ($formLoadTime > 0 && (time() - $formLoadTime) < 3) {
    echo json_encode(['success' => true, 'message' => 'Recenzia a fost trimisă cu succes!']);
    exit;
}

// ===== ANTI-SPAM: IP RATE LIMITING =====
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ipCacheDir = __DIR__ . '/../logs';
if (!is_dir($ipCacheDir)) @mkdir($ipCacheDir, 0755, true);
$ipCacheFile = $ipCacheDir . '/.review_ip_' . md5($ip);
$maxReviewsPerHour = 5;

if (file_exists($ipCacheFile)) {
    $ipData = json_decode(file_get_contents($ipCacheFile), true) ?: [];
    $ipData = array_filter($ipData, fn($t) => (time() - $t) < 3600);
    if (count($ipData) >= $maxReviewsPerHour) {
        http_response_code(429);
        echo json_encode(['success' => false, 'error' => 'Ați atins limita de recenzii. Încercați mai târziu.', 'field' => 'rate_limit']);
        exit;
    }
    $ipData[] = time();
    @file_put_contents($ipCacheFile, json_encode(array_values($ipData)));
} else {
    @file_put_contents($ipCacheFile, json_encode([time()]));
}

// ===== VALIDARE NUME =====
if (mb_strlen($name) < 2) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Numele trebuie să aibă cel puțin 2 caractere.',
        'field' => 'name'
    ]);
    exit;
}

if (mb_strlen($name) > 80) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Numele nu poate depăși 80 de caractere.',
        'field' => 'name'
    ]);
    exit;
}

// ===== ANTI-SPAM: VERIFICARE CUVINTE SPAM =====
$spamWords = ['viagra', 'casino', 'lottery', 'winner', 'click here', 'free money', 'buy now', 'act now', 'limited time', 'http://', 'https://', '.ru/', '.cn/', 'bitcoin', 'crypto', 'investment opportunity'];
$textToCheck = strtolower($name . ' ' . $title . ' ' . $message);
foreach ($spamWords as $word) {
    if (strpos($textToCheck, $word) !== false) {
        echo json_encode(['success' => true, 'message' => 'Recenzia a fost trimisă cu succes!']);
        exit;
    }
}

// ===== ANTI-SPAM: VERIFICARE LINK-URI EXCESIVE =====
if (preg_match_all('/https?:\/\/|www\./i', $message, $matches) > 1) {
    echo json_encode(['success' => true, 'message' => 'Recenzia a fost trimisă cu succes!']);
    exit;
}

// ===== VALIDARE TITLU =====
if (mb_strlen($title) < 5) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Titlul trebuie să aibă cel puțin 5 caractere.',
        'field' => 'title'
    ]);
    exit;
}

if (mb_strlen($title) > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Titlul nu poate depăși 100 de caractere.',
        'field' => 'title'
    ]);
    exit;
}

// ===== VALIDARE MESAJ =====
if (mb_strlen($message) < 20) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Mesajul trebuie să aibă cel puțin 20 de caractere.',
        'field' => 'message'
    ]);
    exit;
}

if (mb_strlen($message) > 1000) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Mesajul nu poate depăși 1000 de caractere.',
        'field' => 'message'
    ]);
    exit;
}

// Verificare spam (text repetitiv)
if (preg_match('/^(.)\1{15,}$/u', $message)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Mesajul pare să fie spam.',
        'field' => 'message'
    ]);
    exit;
}

// ===== VALIDARE RATING =====
if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Vă rugăm selectați un rating între 1 și 5 stele.',
        'field' => 'rating'
    ]);
    exit;
}

// ===== VALIDARE TERMENI ȘI CONDIȚII =====
if (!$terms) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Trebuie să acceptați termenii și condițiile.',
        'field' => 'terms'
    ]);
    exit;
}

// ===== SALVARE ÎN BAZA DE DATE =====
try {
    $pdo = getDB();
    
    // Pregătire query cu prepared statement (securitate SQL injection)
    $stmt = $pdo->prepare('
        INSERT INTO reviews (name, email, title, message, rating, ip_address, user_agent, approved, created_at) 
        VALUES (:name, :email, :title, :message, :rating, :ip, :ua, 0, NOW())
    ');
    
    // Execută inserarea
    $stmt->execute([
        ':name' => $name,
        ':email' => '', // Email eliminat din formular
        ':title' => $title,
        ':message' => $message,
        ':rating' => $rating,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ':ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
    
    // Salvează timestamp pentru rate limiting
    $_SESSION['last_review_at'] = time();
    
    // ===== RĂSPUNS DE SUCCES =====
    echo json_encode([
        'success' => true,
        'message' => 'Recenzia ta a fost trimisă cu succes și va apărea după moderare. Mulțumim!',
        // Returnăm datele pentru afișare imediată (preview)
        'review' => [
            'name' => $name,
            'title' => $title,
            'message' => $message,
            'rating' => $rating,
            'date' => date('d.m.Y'),
            'pending' => true // Indicator că e în așteptare
        ]
    ]);
    
} catch (PDOException $e) {
    // Log eroarea pentru debugging (nu o afișa utilizatorului)
    error_log('Review submission error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'A apărut o eroare la salvarea recenziei. Vă rugăm încercați din nou.',
        'field' => 'server'
    ]);
    exit;
}
