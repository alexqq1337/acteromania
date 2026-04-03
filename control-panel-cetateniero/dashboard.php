<?php
/**
 * ActeRomânia CMS - Admin Dashboard
 */
require_once '../config.php';
requireLogin();

$db = getDB();

// Get counts for dashboard stats
$stats = [
    'services' => $db->query("SELECT COUNT(*) FROM services WHERE enabled = 1")->fetchColumn(),
    'testimonials' => $db->query("SELECT COUNT(*) FROM testimonials WHERE enabled = 1")->fetchColumn(),
    'faq' => $db->query("SELECT COUNT(*) FROM faq WHERE enabled = 1")->fetchColumn(),
    'process_steps' => $db->query("SELECT COUNT(*) FROM process_steps WHERE enabled = 1")->fetchColumn(),
    'reviews_pending' => $db->query("SELECT COUNT(*) FROM reviews WHERE approved = 0")->fetchColumn(),
    'reviews_approved' => $db->query("SELECT COUNT(*) FROM reviews WHERE approved = 1")->fetchColumn(),
];

$currentPage = 'dashboard';
$pageTitle = 'Dashboard';

include 'includes/header.php';
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo $stats['services']; ?></div>
            <div class="stat-label">Servicii Active</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon accent">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo $stats['testimonials']; ?></div>
            <div class="stat-label">Recenzii</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo $stats['faq']; ?></div>
            <div class="stat-label">Întrebări FAQ</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo $stats['process_steps']; ?></div>
            <div class="stat-label">Pași Proces</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon <?php echo $stats['reviews_pending'] > 0 ? 'warning' : 'success'; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo $stats['reviews_approved']; ?> <small style="font-size:0.6em;color:#d97706">(+<?php echo $stats['reviews_pending']; ?> în așteptare)</small></div>
            <div class="stat-label">Recenzii</div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h2 class="card-title">Gestionare Secțiuni Website</h2>
    </div>
    <div class="card-body">
        <div class="sections-grid">
            <a href="<?php echo ADMIN_URL; ?>/sections/hero.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                </div>
                <h3>Acasă</h3>
                <p>Editează titlul principal, subtitlul, imaginea și bara de încredere.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/about.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3>Despre Noi</h3>
                <p>Modifică textul despre companie, imaginea și statisticile animate.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/services.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <h3>Servicii</h3>
                <p>Adaugă, editează sau șterge serviciile oferite.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/process.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <h3>Cum Funcționează</h3>
                <p>Editează pașii procesului de lucru și imaginile asociate.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/why-us.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3>De Ce Noi</h3>
                <p>Gestionează avantajele și punctele de încredere.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/testimonials.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h3>Recenzii</h3>
                <p>Moderează recenziile trimise de utilizatori.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/reviews.php" class="section-card<?php echo $stats['reviews_pending'] > 0 ? ' has-badge' : ''; ?>">
                <?php if ($stats['reviews_pending'] > 0): ?>
                <span class="section-badge"><?php echo $stats['reviews_pending']; ?></span>
                <?php endif; ?>
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <h3>Recenzii</h3>
                <p>Moderează recenziile trimise de utilizatori.</p>
            </a>
            <a href="<?php echo ADMIN_URL; ?>/sections/reviews.php?filter=pending" class="section-card section-card-pending">
                <?php if ($stats['reviews_pending'] > 0): ?>
                <span class="section-badge"><?php echo $stats['reviews_pending']; ?></span>
                <?php endif; ?>
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v10"/><path d="M5 12h14"/></svg>
                </div>
                <h3>Recenzii în așteptare</h3>
                <p>Vezi și aprobă recenziile în așteptare.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/faq.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <h3>FAQ</h3>
                <p>Gestionează întrebările frecvente și răspunsurile.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/sections/contact.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <h3>Contact</h3>
                <p>Editează informațiile de contact și harta Google Maps.</p>
            </a>
            
            <a href="<?php echo ADMIN_URL; ?>/media.php" class="section-card">
                <div class="section-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <h3>Media</h3>
                <p>Gestionează biblioteca de imagini și fișiere.</p>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Acțiuni Rapide</h2>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2">
            <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Vezi Website
            </a>
            <a href="<?php echo ADMIN_URL; ?>/settings.php" class="btn btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Setări
            </a>
            <a href="<?php echo ADMIN_URL; ?>/logout.php" class="btn btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Deconectare
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
