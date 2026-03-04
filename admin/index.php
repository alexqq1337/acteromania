<?php
/**
 * ActeRomânia CMS - Admin Login
 */
require_once '../config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Eroare de securitate. Vă rugăm să încercați din nou.';
    } else {
        // Check credentials from database
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $user['username'];
            $_SESSION['admin_user_id'] = $user['id'];
            
            // Update last login
            $stmt = $db->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Nume de utilizator sau parolă incorectă.';
        }
    }
}

$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | ActeRomânia CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="login-logo">
                    <img src="../img/logo.png" alt="ActeRomânia" style="max-height: 60px; width: auto;">
                </div>
                <h1>Admin Panel</h1>
                <p>Conectați-vă pentru a gestiona conținutul site-ului</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div class="form-group">
                    <label for="username">Nume utilizator</label>
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" id="username" name="username" required placeholder="admin" autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Parola</label>
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-full">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Conectare
                </button>
            </form>
            
            <div class="login-footer">
                <p>Credențiale implicite: <strong>admin</strong> / <strong>password</strong></p>
                <p class="text-muted">Schimbați parola după prima conectare!</p>
            </div>
        </div>
    </div>
</body>
</html>
