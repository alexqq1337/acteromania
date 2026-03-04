<?php
require_once __DIR__ . '/config.php';

// Set timezone for Moldova/Romania
date_default_timezone_set('Europe/Chisinau');

// =============================================
// TELEGRAM CONFIGURATION
// =============================================
// 1. Create bot via @BotFather and get token
// 2. Add bot to your chat/group
// 3. Get chat_id from: https://api.telegram.org/bot<TOKEN>/getUpdates
define('TELEGRAM_BOT_TOKEN', '8748722673:AAHoGUMqzPfjyrYVn2AMWQlSS2gMH80Czxo');
define('TELEGRAM_CHAT_ID', '-1003734447374');
define('TELEGRAM_ENABLED', true); // Set to true when configured

// Start session pentru CAPTCHA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

function contact_log($msg) {
    $dir = __DIR__ . '/logs';
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $file = $dir . '/contact.log';
    $line = date('[Y-m-d H:i:s] ') . $msg . "\n";
    @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
}

/**
 * Rate limiting by IP
 */
function check_rate_limit() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cache_dir = __DIR__ . '/logs';
    if (!is_dir($cache_dir)) @mkdir($cache_dir, 0755, true);
    
    $cache_file = $cache_dir . '/.rate_limit_' . md5($ip);
    $max_requests = 30; // Max 30 requests per IP (increased for testing)
    $time_window = 3600; // Per hour
    
    $now = time();
    $requests = [];
    
    if (file_exists($cache_file)) {
        $data = json_decode(file_get_contents($cache_file), true) ?: [];
        $requests = array_filter($data, function($t) use ($now, $time_window) {
            return ($now - $t) < $time_window;
        });
    }
    
    if (count($requests) >= $max_requests) {
        return false;
    }
    
    $requests[] = $now;
    @file_put_contents($cache_file, json_encode(array_values($requests)));
    return true;
}

/**
 * Send message to Telegram
 */
function send_telegram_message($name, $phone, $service, $message) {
    if (!TELEGRAM_ENABLED || TELEGRAM_BOT_TOKEN === 'YOUR_BOT_TOKEN_HERE') {
        return false;
    }
    
    // Build Telegram message with nice formatting
    $text = "📩 *Cerere nouă de pe site*\n\n";
    $text .= "👤 *Nume:* " . escape_telegram($name) . "\n";
    $text .= "📞 *Telefon:* " . escape_telegram($phone) . "\n";
    
    if (!empty($service)) {
        $text .= "📌 *Serviciu:* " . escape_telegram($service) . "\n";
    }
    
    if (!empty($message)) {
        $text .= "\n📝 *Mesaj:*\n" . escape_telegram($message) . "\n";
    }
    
    $text .= "\n⏰ " . date('d.m.Y H:i');
    
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
    
    $data = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $text,
        'parse_mode' => 'Markdown',
        'disable_web_page_preview' => true
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        contact_log('Telegram error: ' . $response);
        return false;
    }
    
    return true;
}

/**
 * Escape special characters for Telegram Markdown
 */
function escape_telegram($text) {
    return str_replace(['_', '*', '[', ']', '`'], ['\\_', '\\*', '\\[', '\\]', '\\`'], $text);
}

// Read POST (FormData)
$name = isset($_POST['nume']) ? trim($_POST['nume']) : '';
$phone_prefix = isset($_POST['phone_prefix']) ? trim($_POST['phone_prefix']) : '+373';
$phone_raw = isset($_POST['telefon']) ? trim($_POST['telefon']) : '';
$phone = $phone_prefix . ' ' . $phone_raw; // Combine prefix + number
$service = isset($_POST['serviciu']) ? trim($_POST['serviciu']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$recaptcha_response = isset($_POST['g-recaptcha-response']) ? trim($_POST['g-recaptcha-response']) : '';
$source = isset($_POST['source']) ? trim($_POST['source']) : 'contact_form'; // Track form source

// Google reCAPTCHA v2 Secret Key
// Test key for localhost (always passes): 6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
// For production, get real key from: https://www.google.com/recaptcha/admin
define('RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');

// Verify reCAPTCHA
if (empty($recaptcha_response)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => __('error_captcha', 'Please complete the anti-spam verification.')]);
    exit;
}

$verify_url = 'https://www.google.com/recaptcha/api/siteverify';
$verify_data = [
    'secret' => RECAPTCHA_SECRET_KEY,
    'response' => $recaptcha_response,
    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verify_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verify_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$verify_response = curl_exec($ch);
curl_close($ch);

$verify_result = json_decode($verify_response, true);
if (!$verify_result || !isset($verify_result['success']) || $verify_result['success'] !== true) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => __('error_captcha_failed', 'Anti-spam verification failed. Please try again.')]);
    exit;
}

// Log incoming request for debugging
$remote = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 300);
$payload_preview = substr(json_encode($_POST, JSON_UNESCAPED_UNICODE), 0, 2000);
contact_log("INCOMING from {$remote} UA: {$ua} PAYLOAD: {$payload_preview}");

// Check rate limit
if (!check_rate_limit()) {
    http_response_code(429);
    echo json_encode(['success' => false, 'error' => __('error_rate_limit', 'Too many requests. Please try again later.')]);
    exit;
}

if ($name === '' || $phone_raw === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => __('error_fields_required', 'Please fill in the required fields (name, phone).')]);
    exit;
}

// Validate phone number based on country prefix
function validatePhoneNumber($phone, $prefix) {
    $cleanPhone = preg_replace('/\D/', '', $phone);
    
    if (empty($cleanPhone)) {
        return ['valid' => false, 'error' => 'Phone number is required'];
    }
    
    $rules = [
        '+373' => ['length' => 8, 'pattern' => '/^[67]\d{7}$/'],  // Moldova
        '+40'  => ['length' => 9, 'pattern' => '/^7\d{8}$/'],     // Romania
        '+380' => ['length' => 9, 'pattern' => '/^[3-9]\d{8}$/'], // Ukraine
    ];
    
    if (!isset($rules[$prefix])) {
        // Default: at least 7 digits
        return strlen($cleanPhone) >= 7 ? ['valid' => true] : ['valid' => false, 'error' => 'Invalid phone number'];
    }
    
    $rule = $rules[$prefix];
    
    if (strlen($cleanPhone) !== $rule['length']) {
        return ['valid' => false, 'error' => 'Phone number must be ' . $rule['length'] . ' digits for ' . $prefix];
    }
    
    if (!preg_match($rule['pattern'], $cleanPhone)) {
        return ['valid' => false, 'error' => 'Invalid phone number format for ' . $prefix];
    }
    
    return ['valid' => true];
}

$phoneValidation = validatePhoneNumber($phone_raw, $phone_prefix);
if (!$phoneValidation['valid']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => __('error_phone_invalid', $phoneValidation['error'] ?? 'Invalid phone number.')]);
    exit;
}

// Save to contacts_history database
try {
    $stmt = $pdo->prepare("INSERT INTO contacts_history (name, phone, service, source, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $name,
        $phone,
        $service,
        $source,
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500)
    ]);
    contact_log("Saved contact to DB: {$name}, {$phone}, {$service}");
} catch (Exception $e) {
    contact_log("DB save error: " . $e->getMessage());
    // Continue anyway - don't fail the request
}

// Send to Telegram
send_telegram_message($name, $phone, $service, $message);

// Build email content
$owner = 'vasilepopovici262@gmail.com';
$subject = 'Mesaj contact de pe site: ' . ($service ?: 'formular general');
$body = "Ați primit un mesaj din formularul de contact:\n\n";
$body .= "Nume: " . $name . "\n";
$body .= "Telefon: " . $phone . "\n";
if ($service !== '') $body .= "Serviciu: " . $service . "\n";
$body .= "\nMesaj:\n" . $message . "\n";

// Prefer Composer autoload if available
$vendor_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($vendor_autoload)) {
    require_once $vendor_autoload;
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $smtp_user = 'vasilepopovici262@gmail.com';
        $smtp_pass = str_replace(' ', '', 'twhz cgmp wvsw ntbb');

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($smtp_user, 'Website Contact');
        $mail->addAddress($owner);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();

        echo json_encode(['success' => true, 'message' => __('form_success', 'Message sent successfully.')]);
        exit;
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        contact_log('PHPMailer (composer) error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => __('error_email_send', 'Error sending email. Please try again.')]);
        exit;
    }
}

// Fallback to bundled includes if present
$phpmailer_base = __DIR__ . '/includes/PHPMailer/src/';
if (file_exists($phpmailer_base . 'PHPMailer.php') && file_exists($phpmailer_base . 'SMTP.php')) {
    try {
        require_once $phpmailer_base . 'Exception.php';
        require_once $phpmailer_base . 'PHPMailer.php';
        require_once $phpmailer_base . 'SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $smtp_user = 'vasilepopovici262@gmail.com';
        $smtp_pass = str_replace(' ', '', 'twhz cgmp wvsw ntbb');

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($smtp_user, 'Website Contact');
        $mail->addAddress($owner);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();

        echo json_encode(['success' => true, 'message' => __('form_success', 'Message sent successfully.')]);
        exit;
    } catch (Exception $e) {
        contact_log('PHPMailer (includes) error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => __('error_email_send', 'Error sending email. Please try again.')]);
        exit;
    }
}

// Final fallback: write to logs
contact_log("PHPMailer missing - contact message from {$name}: " . str_replace("\n", ' | ', $body));
echo json_encode(['success' => true, 'message' => __('form_success', 'Message sent successfully.')]);
exit;

?>
