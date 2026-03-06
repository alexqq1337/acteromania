<?php
/**
 * ActeRomânia CMS - Admin Header
 */
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?>ActeRomânia CMS</title>
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/img/CR.png">
    <link rel="apple-touch-icon" href="<?php echo SITE_URL; ?>/img/CR.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="<?php echo ADMIN_URL; ?>/dashboard.php" class="sidebar-logo">
                    <img src="<?php echo SITE_URL; ?>/img/logo2.png" alt="ActeRomânia">
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <a href="<?php echo ADMIN_URL; ?>/dashboard.php" class="nav-item <?php echo ($currentPage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Dashboard
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Secțiuni Website</div>
                    <a href="<?php echo ADMIN_URL; ?>/sections/hero.php" class="nav-item <?php echo ($currentPage ?? '') === 'hero' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                        Acasă
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/about.php" class="nav-item <?php echo ($currentPage ?? '') === 'about' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        Despre Noi
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/services.php" class="nav-item <?php echo ($currentPage ?? '') === 'services' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Servicii
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/process.php" class="nav-item <?php echo ($currentPage ?? '') === 'process' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Cum Funcționează
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/why-us.php" class="nav-item <?php echo ($currentPage ?? '') === 'why-us' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        De Ce Noi
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/reviews.php" class="nav-item <?php echo ($currentPage ?? '') === 'reviews' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Recenzii
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/faq.php" class="nav-item <?php echo ($currentPage ?? '') === 'faq' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        FAQ
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/contact.php" class="nav-item <?php echo ($currentPage ?? '') === 'contact' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Contact
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Sistem</div>
                    <a href="<?php echo ADMIN_URL; ?>/sections/contacts.php" class="nav-item <?php echo ($currentPage ?? '') === 'contacts' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Istoric Contacte
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/media.php" class="nav-item <?php echo ($currentPage ?? '') === 'media' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Media
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/sections/translations.php" class="nav-item <?php echo ($currentPage ?? '') === 'translations' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 8l6 6"/><path d="M4 14l6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="M22 22l-5-10-5 10"/><path d="M14 18h6"/></svg>
                        Traduceri
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/settings.php" class="nav-item <?php echo ($currentPage ?? '') === 'settings' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Setări
                    </a>
                    <a href="<?php echo ADMIN_URL; ?>/logout.php" class="nav-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Deconectare
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <h1 class="page-title"><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h1>
                </div>
                <div class="header-right">
                    <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-outline btn-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Vezi Site
                    </a>
                    <div class="user-menu">
                        <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_user'] ?? 'A', 0, 1)); ?></div>
                        <div class="user-info">
                            <div class="user-name"><?php echo $_SESSION['admin_user'] ?? 'Admin'; ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <div class="admin-content">
                <!-- Toast container for notifications -->
                <div class="toast-container" id="toastContainer"></div>
                
                <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?>">
                    <?php 
                    echo $_SESSION['flash_message']; 
                    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
                    ?>
                </div>
                <?php endif; ?>
