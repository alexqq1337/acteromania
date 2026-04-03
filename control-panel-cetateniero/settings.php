<?php
/**
 * ActeRomânia CMS - Settings
 */
require_once '../config.php';
requireLogin();

$pageTitle = 'Setări';
$currentPage = 'settings';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: settings.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_settings') {
        try {
            $settings = [
                'site_name' => sanitizeInput($_POST['site_name']),
                'site_tagline' => sanitizeInput($_POST['site_tagline']),
                'meta_description' => sanitizeInput($_POST['meta_description']),
                'meta_keywords' => sanitizeInput($_POST['meta_keywords']),
                'footer_text' => sanitizeInput($_POST['footer_text']),
                'facebook_url' => sanitizeInput($_POST['facebook_url']),
                'instagram_url' => sanitizeInput($_POST['instagram_url']),
                'linkedin_url' => sanitizeInput($_POST['linkedin_url']),
                'twitter_url' => sanitizeInput($_POST['twitter_url'])
            ];
            
            foreach ($settings as $key => $value) {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                                       ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$key, $value, $value]);
            }
            
            $_SESSION['flash_message'] = 'Setările au fost actualizate!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'change_password') {
        try {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            $adminId = (int) ($_SESSION['admin_user_id'] ?? 0);
            if ($adminId <= 0) {
                throw new Exception('Sesiune invalidă. Autentificați-vă din nou.');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Parolele nu coincid!');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Parola trebuie să aibă minim 8 caractere!');
            }
            
            // Verify current password (coloana în DB este password_hash)
            $stmt = $pdo->prepare('SELECT password_hash FROM admin_users WHERE id = ?');
            $stmt->execute([$adminId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
                throw new Exception('Parola curentă este incorectă!');
            }
            
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?');
            $stmt->execute([$hashedPassword, $adminId]);
            
            $_SESSION['flash_message'] = 'Parola a fost schimbată cu succes!';
            $_SESSION['flash_type'] = 'success';
        } catch (Exception $e) {
            $_SESSION['flash_message'] = $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: settings.php');
    exit;
}

// Get current settings
function getSetting($key, $default = '') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['setting_value'] : $default;
}

include 'includes/header.php';
?>

<div class="settings-grid">
    <div class="settings-main">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="update_settings">
            
            <div class="content-card">
                <div class="card-header">
                    <h2>Setări Generale</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="site_name">Nume Site</label>
                        <input type="text" id="site_name" name="site_name" class="form-control" 
                               value="<?php echo htmlspecialchars(getSetting('site_name', 'ActeRomânia')); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="site_tagline">Slogan</label>
                        <input type="text" id="site_tagline" name="site_tagline" class="form-control" 
                               value="<?php echo htmlspecialchars(getSetting('site_tagline', 'Servicii Juridice de Încredere')); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="footer_text">Text Footer</label>
                        <input type="text" id="footer_text" name="footer_text" class="form-control" 
                               value="<?php echo htmlspecialchars(getSetting('footer_text', '© 2024 ActeRomânia. Toate drepturile rezervate.')); ?>">
                    </div>
                </div>
            </div>
            
            <div class="content-card mt-4">
                <div class="card-header">
                    <h2>SEO & Meta</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="3" 
                                  maxlength="160"><?php echo htmlspecialchars(getSetting('meta_description')); ?></textarea>
                        <small class="form-text">Max 160 caractere. Descrierea care apare în rezultatele Google.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" 
                               value="<?php echo htmlspecialchars(getSetting('meta_keywords')); ?>"
                               placeholder="cuvânt1, cuvânt2, cuvânt3">
                    </div>
                </div>
            </div>
            
            <div class="content-card mt-4">
                <div class="card-header">
                    <h2>Social Media</h2>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="facebook_url">Facebook URL</label>
                            <input type="url" id="facebook_url" name="facebook_url" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('facebook_url')); ?>"
                                   placeholder="https://facebook.com/...">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="instagram_url">Instagram URL</label>
                            <input type="url" id="instagram_url" name="instagram_url" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('instagram_url')); ?>"
                                   placeholder="https://instagram.com/...">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="linkedin_url">LinkedIn URL</label>
                            <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('linkedin_url')); ?>"
                                   placeholder="https://linkedin.com/...">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="twitter_url">Twitter URL</label>
                            <input type="url" id="twitter_url" name="twitter_url" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('twitter_url')); ?>"
                                   placeholder="https://twitter.com/...">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Salvează Setările
                </button>
            </div>
        </form>
    </div>
    
    <div class="settings-sidebar">
        <div class="content-card">
            <div class="card-header">
                <h2>Schimbă Parola</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label for="current_password">Parolă Curentă</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Parolă Nouă</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" 
                               minlength="8" required>
                        <small class="form-text">Minim 8 caractere</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmă Parola</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-outline btn-block">Schimbă Parola</button>
                </form>
            </div>
        </div>
        
        <div class="content-card mt-4">
            <div class="card-header">
                <h2>Informații Sistem</h2>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Versiune PHP</span>
                        <span class="info-value"><?php echo PHP_VERSION; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">MySQL</span>
                        <span class="info-value"><?php echo $pdo->getAttribute(PDO::ATTR_SERVER_VERSION); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Upload Max</span>
                        <span class="info-value"><?php echo ini_get('upload_max_filesize'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">CMS Versiune</span>
                        <span class="info-value">1.0.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

.form-row { display: flex; gap: 1.5rem; margin-bottom: 1rem; }
.form-row .form-group { flex: 1; margin-bottom: 0; }
.col-md-6 { flex: 0 0 calc(50% - 0.75rem); }
.mt-4 { margin-top: 2rem; }

.btn-block {
    width: 100%;
    justify-content: center;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1rem;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e2e8f0;
}
.info-item:last-child { border-bottom: none; }

.info-label {
    color: #64748b;
    font-size: 0.875rem;
}

.info-value {
    font-weight: 500;
    font-size: 0.875rem;
}

@media (max-width: 1024px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .form-row { flex-direction: column; gap: 0; }
    .col-md-6 { flex: 0 0 100%; }
}
</style>

<?php include 'includes/footer.php'; ?>
