<?php
/**
 * 
 *  - Dynamic Homepage
 * All content is loaded from the database via the CMS
 */

// Start output buffering immediately to prevent "headers already sent" errors
ob_start();

// Google reCAPTCHA v2 Site Key
// Test key for localhost (always passes): 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
// For production, get real key from: https://www.google.com/recaptcha/admin
define('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');

require_once __DIR__ . '/config.php';

// Get current language (must be before any output)
$currentLang = getCurrentLanguage();
$translations = loadTranslations($currentLang);

// Force UTF-8
header('Content-Type: text/html; charset=utf-8');

// Fetch all content from database with translations
try {
    $pdo = getDB();
    
    // Hero section - with translations
    try {
        $hero = getHeroTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $hero = null;
    }
    if (!$hero) {
        $hero = $pdo->query("SELECT * FROM hero LIMIT 1")->fetch();
    }
    
    try {
        $heroTrustItems = getHeroTrustItemsTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $heroTrustItems = [];
    }
    if (empty($heroTrustItems)) {
        $heroTrustItems = $pdo->query("SELECT * FROM hero_trust_items WHERE enabled = 1 ORDER BY sort_order ASC")->fetchAll();
    }
    
    // About section - with translations
    try {
        $about = getAboutTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $about = null;
    }
    if (!$about) {
        $about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch();
    }
    
    try {
        $aboutStats = getAboutStatsTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $aboutStats = [];
    }
    if (empty($aboutStats)) {
        $aboutStats = $pdo->query("SELECT * FROM about_stats WHERE enabled = 1 ORDER BY sort_order ASC")->fetchAll();
    }
    
    // Services section - with translations
    try {
        $servicesSection = getServicesSectionTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $servicesSection = null;
    }
    if (!$servicesSection) {
        $servicesSection = $pdo->query("SELECT * FROM services_section LIMIT 1")->fetch();
    }
    
    try {
        $services = getServicesTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $services = [];
    }
    if (empty($services)) {
        $services = $pdo->query("SELECT * FROM services WHERE enabled = 1 ORDER BY sort_order ASC")->fetchAll();
    }
    
    // Process section - with translations
    try {
        $processSection = getProcessSectionTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $processSection = null;
    }
    if (!$processSection) {
        $processSection = $pdo->query("SELECT * FROM process_section LIMIT 1")->fetch();
    }
    
    try {
        $processSteps = getProcessStepsTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $processSteps = [];
    }
    if (empty($processSteps)) {
        $processSteps = $pdo->query("SELECT * FROM process_steps WHERE enabled = 1 ORDER BY sort_order ASC")->fetchAll();
    }
    
    // Why Us section - with translations
    try {
        $whyUs = getWhyUsTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $whyUs = null;
    }
    if (!$whyUs) {
        $whyUs = $pdo->query("SELECT * FROM why_us LIMIT 1")->fetch();
    }
    
    try {
        $whyUsItems = getWhyUsItemsTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $whyUsItems = [];
    }
    if (empty($whyUsItems)) {
        $whyUsItems = $pdo->query("SELECT * FROM why_us_items WHERE enabled = 1 ORDER BY sort_order ASC")->fetchAll();
    }
    
    // Reviews section (recenzii de la utilizatori, doar cele aprobate)
    $reviews = [];
    try {
        $reviews = $pdo->query("SELECT id, name, title, message, rating, created_at FROM reviews WHERE approved = 1 ORDER BY created_at DESC LIMIT 12")->fetchAll();
    } catch (PDOException $e) {
        // Tabelul reviews nu există încă
    }
    
    // FAQ section - with translations
    try {
        $faqSection = getFaqSectionTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $faqSection = null;
    }
    if (!$faqSection) {
        $faqSection = $pdo->query("SELECT * FROM faq_section LIMIT 1")->fetch();
    }
    
    try {
        $faqs = getFaqTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $faqs = [];
    }
    if (empty($faqs)) {
        $faqs = $pdo->query("SELECT * FROM faq WHERE enabled = 1 ORDER BY sort_order ASC")->fetchAll();
    }
    
    // Contact section - with translations
    try {
        $contact = getContactTranslated($pdo, $currentLang);
    } catch (PDOException $e) {
        $contact = null;
    }
    if (!$contact) {
        $contact = $pdo->query("SELECT * FROM contact WHERE id = 1 LIMIT 1")->fetch();
    }

    // Fallback helpers for contact display (do not overwrite DB values)
    $default_office = '+37378625055';
    $default_address = 'Ismail 33, Euro Credit Bank, Et. 6, Cab. 612, Chișinău';
    $office_raw = !empty($contact['office_phone']) ? $contact['office_phone'] : (!empty($contact['phone']) ? $contact['phone'] : $default_office);
    $office_digits = preg_replace('/[^0-9]/', '', $office_raw);
    $whatsapp_raw = !empty($contact['whatsapp']) ? $contact['whatsapp'] : $office_raw;
    $whatsapp_digits = preg_replace('/[^0-9]/', '', $whatsapp_raw);
    $viber_raw = !empty($contact['viber']) ? $contact['viber'] : $office_raw;
    $viber_digits = preg_replace('/[^0-9]/', '', $viber_raw);
    $email_raw = !empty($contact['email']) ? $contact['email'] : 'contact@acteromania.ro';
    $map_default_iframe = '<iframe src="https://www.google.com/maps?q=' . rawurlencode($default_address) . '&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
    
    // Settings
    $settingsResult = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
    $settings = [];
    foreach ($settingsResult as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
} catch (PDOException $e) {
    die("Error loading content: " . $e->getMessage());
}

// Helper function to safely output content
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Helper function to render stars
function renderStars($rating) {
    $stars = '';
    for ($i = 0; $i < $rating; $i++) {
        $stars .= '★';
    }
    return $stars;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($settings['site_title'] ?? 'CetățeniaRomână - Asistență Legală pentru Documente Românești'); ?></title>
    <meta name="description" content="<?php echo e($settings['meta_description'] ?? 'CetățeniaRomână - Servicii profesionale de asistență juridică pentru obținerea documentelor românești. Cetățenie, pașaport, buletin și consultanță juridică.'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo $currentLang; ?>" async defer></script>
    <script>
        // Pass translations to JavaScript
        window.TRANSLATIONS = <?php echo json_encode($translations, JSON_UNESCAPED_UNICODE); ?>;
        window.CURRENT_LANG = '<?php echo $currentLang; ?>';
    </script>
</head>
<body>
    <!-- HEADER -->
    <header class="header" id="header">
        <img src="https://flagcdn.com/w40/eu.png" alt="Uniunea Europeană" class="header-flag header-flag-left" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 24px; height: 16px;">
        <div class="container">
            <a href="#" class="logo">
                <img src="/img/logo.png" alt="Cetățenia Română Logo" class="logo-img" style="height:90px;max-width:400px;object-fit:contain;"/>
            </a>
            <nav class="nav" id="nav">
                <ul class="nav-list">
                    <li><a href="#acasa" class="nav-link"><?php _e('nav_home'); ?></a></li>
                    <li><a href="#despre" class="nav-link"><?php _e('nav_about'); ?></a></li>
                    <li><a href="#servicii" class="nav-link"><?php _e('nav_services'); ?></a></li>
                    <li><a href="#proces" class="nav-link"><?php _e('nav_process'); ?></a></li>
                    <li><a href="#recenzii" class="nav-link"><?php _e('nav_testimonials'); ?></a></li>
                    <li><a href="#faq" class="nav-link"><?php _e('nav_faq'); ?></a></li>
                    <li><a href="#contact" class="nav-link nav-link--cta"><?php _e('nav_contact'); ?></a></li>
                </ul>
                <!-- Language Selector -->
                <div class="language-selector">
                    <button class="language-btn" id="langBtn">
                        <span class="lang-current"><?php echo strtoupper($currentLang); ?></span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="language-dropdown" id="langDropdown">
                        <?php 
                        // Build URL preserving current path and hash
                        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?');
                        foreach (AVAILABLE_LANGUAGES as $lang): 
                        ?>
                        <a href="<?php echo $currentUrl; ?>?lang=<?php echo $lang; ?>" class="language-option <?php echo $lang === $currentLang ? 'active' : ''; ?>">
                            <span class="lang-code"><?php echo strtoupper($lang); ?></span>
                            <span class="lang-name"><?php echo getLanguageName($lang); ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="<?php _e('nav_menu'); ?>">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <img src="https://flagcdn.com/w40/ro.png" alt="România" class="header-flag header-flag-right" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); width: 24px; height: 16px;">
    </header>

    <!-- HERO SECTION -->
    <section class="hero" id="acasa">
        <div class="hero-bg">
            <?php if (($hero['media_type'] ?? 'image') === 'video' && !empty($hero['video_url'])): ?>
                <?php 
                $videoType = $hero['video_type'] ?? 'upload';
                $videoUrl = $hero['video_url'];
                if ($videoType === 'youtube'):
                    // Extract YouTube video ID
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches);
                    $youtubeId = $matches[1] ?? '';
                ?>
                    <div class="hero-video-wrapper" style="filter: blur(6px) brightness(0.95) saturate(120%);">
                        <iframe 
                            src="https://www.youtube.com/embed/<?php echo e($youtubeId); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo e($youtubeId); ?>&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1"
                            frameborder="0"
                            allow="autoplay; encrypted-media"
                            allowfullscreen
                            class="hero-video-iframe">
                        </iframe>
                    </div>
                <?php elseif ($videoType === 'vimeo'):
                    // Extract Vimeo video ID
                    preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $videoUrl, $matches);
                    $vimeoId = $matches[1] ?? '';
                ?>
                    <div class="hero-video-wrapper" style="filter: blur(6px) brightness(0.95) saturate(120%);">
                        <iframe 
                            src="https://player.vimeo.com/video/<?php echo e($vimeoId); ?>?autoplay=1&muted=1&loop=1&background=1&quality=1080p"
                            frameborder="0"
                            allow="autoplay; fullscreen"
                            class="hero-video-iframe">
                        </iframe>
                    </div>
                <?php else: ?>
                    <video autoplay muted playsinline style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; filter: blur(6px) brightness(0.95) saturate(120%);">
                        <source src="<?php echo e($videoUrl); ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            <?php elseif (!empty($hero['image_url'])): ?>
                <img src="<?php echo e($hero['image_url']); ?>" alt="Consultație legală profesională" loading="eager" style="filter: blur(6px) brightness(0.95) saturate(120%);">
            <?php endif; ?>
            <div class="hero-overlay" style="background: none;"></div>
        </div>
        <div class="container">
            <div class="hero-content fade-in">
                <h1 class="hero-title"><?php echo e(t($hero, 'title') ?: 'Asistență legală pentru documente românești'); ?></h1>
                <p class="hero-subtitle"><?php echo e(t($hero, 'subtitle')); ?></p>
                
                <?php if (!empty($hero['trust_bar_enabled']) && !empty($heroTrustItems)): ?>
                <div class="hero-trust-bar">
                    <?php foreach ($heroTrustItems as $item): ?>
                    <div class="trust-item">
                        <?php if (!empty($item['icon_svg'])): ?>
                            <?php echo html_entity_decode($item['icon_svg']); ?>
                        <?php endif; ?>
                        <span><?php echo e(t($item, 'text')); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <button type="button" class="btn btn-primary btn-lg" id="consultationFormBtn"><?php echo e(t($hero, 'cta_text') ?: __('hero_cta_consultation')); ?></button>
            </div>
        </div>
    </section>

    <!-- SLIDE-IN CONSULTATION FORM -->
    <div class="consultation-form-overlay" id="consultationFormOverlay"></div>
    <div class="consultation-form-panel" id="consultationFormPanel">
        <div class="consultation-form-header">
            <h3 class="consultation-form-title"><?php _e('consultation_title'); ?></h3>
            <button type="button" class="consultation-form-close" id="consultationFormClose" aria-label="<?php _e('consultation_close'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form class="consultation-form" id="consultationForm" novalidate>
            <input type="hidden" name="source" value="consultation_form">
            <div class="form-group">
                <label for="consultation-nume"><?php _e('form_name'); ?></label>
                <input type="text" id="consultation-nume" name="nume" placeholder="<?php _e('form_name_placeholder'); ?>" required maxlength="100">
                <span class="form-error" id="error-nume"></span>
            </div>
            <div class="form-group">
                <label for="consultation-serviciu"><?php _e('form_service'); ?></label>
                <select id="consultation-serviciu" name="serviciu" required>
                    <option value=""><?php _e('form_service_placeholder'); ?></option>
                    <?php foreach ($services as $service): ?>
                    <option value="<?php echo e(strtolower($service['title'])); ?>"><?php echo e(t($service, 'title')); ?></option>
                    <?php endforeach; ?>
                    <option value="altele"><?php _e('form_service_other'); ?></option>
                </select>
                <span class="form-error" id="error-serviciu"></span>
            </div>
            <div class="form-group">
                <label for="consultation-telefon"><?php _e('form_phone'); ?></label>
                <div class="phone-input-group">
                    <select name="phone_prefix" class="phone-prefix-select" id="consultation-prefix">
                        <option value="+373"><?php _e('phone_prefix_md'); ?></option>
                        <option value="+40"><?php _e('phone_prefix_ro'); ?></option>
                        <option value="+380"><?php _e('phone_prefix_ua'); ?></option>
                    </select>
                    <input type="tel" id="consultation-telefon" name="telefon" placeholder="<?php _e('form_phone_placeholder'); ?>" required class="phone-number-input">
                </div>
                <span class="form-error" id="error-telefon"></span>
            </div>
            <div class="form-group recaptcha-wrapper">
                <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                <span class="form-error" id="error-recaptcha"></span>
            </div>
            <button type="submit" class="btn btn-primary btn-full"><?php _e('form_submit'); ?></button>
            <p class="form-note"><?php _e('form_note'); ?></p>
        </form>
    </div>

    <!-- ABOUT SECTION -->
    <section class="section about" id="despre">
        <div class="container">
            <div class="about-grid">
                <div class="about-image fade-in-left">
                    <?php if (!empty($about['image_url'])): ?>
                    <img src="<?php echo e($about['image_url']); ?>" alt="Despre Cetățenia Română">
                    <?php endif; ?>
                </div>
                <div class="about-content fade-in-right">
                    <span class="section-label"><?php echo e(t($about, 'section_label') ?: __('about_section_label')); ?></span>
                    <h2 class="section-title"><?php echo e(t($about, 'title')); ?></h2>
                    <div class="about-text">
                        <?php
                        // Admin allows HTML for the About content. Render stored HTML safely here.
                        // We allow a small set of tags and strip others to avoid breaking layout.
                        $aboutContent = t($about, 'content');
                        echo strip_tags($aboutContent, '<p><br><strong><em><ul><ol><li><a><h2><h3>');
                        ?>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($about['counters_enabled']) && !empty($aboutStats)): ?>
            <!-- Animated Counters Section -->
            <div class="counters-section fade-in">
                <?php foreach ($aboutStats as $stat): ?>
                <div class="counter-item">
                    <div class="counter-icon">
                        <?php echo isset($stat['icon_svg']) ? html_entity_decode($stat['icon_svg']) : ''; ?>
                    </div>
                    <span class="counter-number" data-target="<?php echo e($stat['number_value']); ?>">0</span>
                    <span class="counter-suffix"><?php echo e(t($stat, 'suffix')); ?></span>
                    <span class="counter-label"><?php echo e(t($stat, 'label')); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section class="section services" id="servicii">
        <div class="container">
            <div class="section-header text-center fade-in">
                <span class="section-label"><?php echo e(t($servicesSection, 'section_label') ?: __('services_section_label')); ?></span>
                <h2 class="section-title"><?php echo e(t($servicesSection, 'title')); ?></h2>
                <p class="section-description"><?php echo e(t($servicesSection, 'description')); ?></p>
            </div>
            <div class="services-scroll-container" id="servicesScrollContainer">
                <div class="services-grid" id="servicesGrid">
                    <?php foreach ($services as $index => $service): ?>
                    <?php 
                    // Prepare translated service data for JavaScript modal
                    $serviceForJs = $service;
                    $serviceForJs['title_original'] = strtolower($service['title']); // Original title for dropdown matching
                    $serviceForJs['title'] = t($service, 'title');
                    // Use 'description' column (fallback from short_description/full_description)
                    $serviceForJs['short_description'] = t($service, 'short_description') ?: t($service, 'description');
                    $serviceForJs['full_description'] = t($service, 'full_description') ?: t($service, 'description');
                    $serviceForJs['features'] = t($service, 'features');
                    ?>
                    <div class="service-card fade-in <?php echo $index >= 8 ? 'service-hidden' : ''; ?>" 
                         data-service-id="<?php echo $service['id']; ?>"
                         onclick="openServiceModal(<?php echo htmlspecialchars(json_encode($serviceForJs)); ?>)">
                        <div class="service-image">
                            <?php if (!empty($service['image_url'])): ?>
                            <img src="<?php echo e($service['image_url']); ?>" alt="<?php echo e(t($service, 'title')); ?>" class="service-main-image">
                            <?php elseif (!empty($service['icon_svg'])): ?>
                            <span class="service-icon-svg"><?php echo html_entity_decode($service['icon_svg']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="service-content">
                            <h3 class="service-title"><?php echo e(t($service, 'title')); ?></h3>
                            <p class="service-description"><?php echo e(t($service, 'short_description') ?: t($service, 'description')); ?></p>
                            <span class="service-more-btn"><?php _e('services_learn_more'); ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-7-7 7 7-7 7"/></svg></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if (count($services) > 4): ?>
            <div class="services-show-more-container fade-in">
                <button type="button" class="btn btn-outline-primary services-show-more" id="showMoreServices" onclick="toggleAllServices()">
                    <span class="show-more-text"><?php _e('services_show_all'); ?> (<?php echo count($services); ?>)</span>
                    <span class="show-less-text" style="display:none;"><?php _e('services_hide'); ?></span>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Service Detail Modal -->
    <div class="service-modal-overlay" id="serviceDetailOverlay" onclick="closeServiceDetailModal()"></div>
    <div class="service-modal" id="serviceDetailModal">
        <button type="button" class="service-modal-close" onclick="closeServiceDetailModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="service-modal-image" id="serviceModalImage"></div>
        <div class="service-modal-content">
            <h3 class="service-modal-title" id="serviceModalTitle"></h3>
            <p class="service-modal-description" id="serviceModalDescription"></p>
            <ul class="service-modal-features" id="serviceModalFeatures"></ul>
            <button type="button" class="btn btn-primary" onclick="requestSelectedService()">
                <?php _e('services_request_service'); ?>
            </button>
        </div>
    </div>

    <!-- HOW IT WORKS SECTION -->
    <section class="section process" id="proces">
        <div class="container">
            <div class="section-header text-center fade-in">
                <span class="section-label"><?php echo e(t($processSection, 'section_label') ?: __('process_section_label')); ?></span>
                <h2 class="section-title"><?php echo e(t($processSection, 'title')); ?></h2>
                <p class="section-description"><?php echo e(t($processSection, 'description')); ?></p>
            </div>
            
            <div class="process-timeline fade-in">
                <?php foreach ($processSteps as $index => $step): ?>
                <div class="timeline-step">
                    <div class="timeline-content<?php echo ($index % 2 == 1) ? ' timeline-content-reverse' : ''; ?>">
                        <div class="timeline-image-container">
                            <?php if (!empty($step['image_url'])): ?>
                            <img src="<?php echo e($step['image_url']); ?>" alt="<?php echo e(t($step, 'title')); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="timeline-text">
                            <span class="step-badge"><?php _e('process_step'); ?> <?php echo $step['step_number']; ?></span>
                            <h3 class="timeline-title"><?php echo e(t($step, 'title')); ?></h3>
                            <p class="timeline-description"><?php echo e(t($step, 'description')); ?></p>
                            <?php if (!empty($step['features']) || !empty($step['features_' . $currentLang])): ?>
                            <ul class="timeline-features">
                                <?php 
                                $featuresJson = t($step, 'features');
                                $features = json_decode($featuresJson, true);
                                if (is_array($features)):
                                    foreach ($features as $feature): ?>
                                <li><?php echo e($feature); ?></li>
                                <?php endforeach;
                                endif; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($index < count($processSteps) - 1): ?>
                    <div class="timeline-connector"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US SECTION -->
    <section class="section why-us">
        <div class="container">
            <div class="why-us-grid">
                <div class="why-us-content fade-in-left">
                    <span class="section-label"><?php echo e(t($whyUs, 'section_label') ?: __('why_us_section_label')); ?></span>
                    <h2 class="section-title"><?php echo e(t($whyUs, 'title')); ?></h2>
                    <div class="why-us-features">
                        <?php foreach ($whyUsItems as $item): ?>
                        <div class="feature-item">
                            <div class="feature-icon">
                                        <?php echo isset($item['icon_svg']) ? html_entity_decode($item['icon_svg']) : ''; ?>
                                    </div>
                            <div class="feature-text">
                                <h4><?php echo e(t($item, 'title')); ?></h4>
                                <p><?php echo e(t($item, 'description')); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="why-us-image fade-in-right">
                    <?php if (!empty($whyUs['image_url'])): ?>
                    <img src="<?php echo e($whyUs['image_url']); ?>" alt="<?php _e('why_us_image_alt'); ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- REVIEWS SECTION (Recenzii de la utilizatori) -->
    <section class="section testimonials" id="recenzii">
        <div class="container">
            <div class="section-header text-center fade-in">
                <span class="section-label"><?php _e('reviews_section_label'); ?></span>
                <h2 class="section-title"><?php _e('reviews_title'); ?></h2>
                <p class="section-description"><?php _e('reviews_description'); ?></p>
            </div>
            
            <!-- Buton mare pentru adăugare recenzie -->
            <div class="reviews-cta text-center">
                <button type="button" class="btn btn-primary btn-lg" id="openReviewModal">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <?php _e('reviews_write_review'); ?>
                </button>
            </div>
            
            <!-- Lista de recenzii aprobate -->
            <div class="testimonials-grid" id="reviews-container">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                    <div class="testimonial-card fade-in">
                        <div class="testimonial-content">
                            <div class="testimonial-stars"><?php echo renderStars($review['rating']); ?></div>
                            <?php if (!empty($review['title'])): ?>
                            <h4 class="testimonial-title"><?php echo e($review['title']); ?></h4>
                            <?php endif; ?>
                            <p class="testimonial-text">"<?php echo e($review['message']); ?>"</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar"><?php echo strtoupper(mb_substr($review['name'], 0, 1)); ?></div>
                            <div class="author-info">
                                <h4><?php echo e($review['name']); ?></h4>
                                <span><?php echo date('d.m.Y', strtotime($review['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="reviews-empty">
                        <p><?php _e('reviews_empty'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Modal pentru adăugare recenzie -->
    <div class="review-modal" id="reviewModal" aria-hidden="true">
        <div class="review-modal-backdrop" id="reviewModalBackdrop"></div>
        <div class="review-modal-container">
            <div class="review-modal-content">
                <button type="button" class="review-modal-close" id="closeReviewModal" aria-label="<?php _e('close'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <h3 class="review-modal-title"><?php _e('reviews_write_review'); ?></h3>
                <p class="review-modal-subtitle"><?php _e('reviews_modal_subtitle'); ?></p>
                
                <form class="review-form" id="reviewForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e(generateCSRFToken()); ?>">
                    <input type="hidden" name="_ft" id="review-ft" value="">
                    
                    <!-- Câmp honeypot (ascuns, anti-spam) -->
                    <div class="review-hp" aria-hidden="true">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    
                    <!-- Nume -->
                    <div class="review-field">
                        <label for="review-name"><?php _e('reviews_form_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="review-name" name="name" placeholder="<?php _e('reviews_form_name_placeholder'); ?>" required maxlength="80">
                        <span class="review-field-error" id="error-name"></span>
                    </div>
                    
                    <!-- Rating stele -->
                    <div class="review-field">
                        <label><?php _e('reviews_form_rating'); ?> <span class="required">*</span></label>
                        <div class="review-rating" id="review-rating">
                            <input type="radio" id="star-5" name="rating" value="5">
                            <label for="star-5" title="<?php _e('reviews_star_5'); ?>">★</label>
                            <input type="radio" id="star-4" name="rating" value="4">
                            <label for="star-4" title="<?php _e('reviews_star_4'); ?>">★</label>
                            <input type="radio" id="star-3" name="rating" value="3">
                            <label for="star-3" title="<?php _e('reviews_star_3'); ?>">★</label>
                            <input type="radio" id="star-2" name="rating" value="2">
                            <label for="star-2" title="<?php _e('reviews_star_2'); ?>">★</label>
                            <input type="radio" id="star-1" name="rating" value="1">
                            <label for="star-1" title="<?php _e('reviews_star_1'); ?>">★</label>
                        </div>
                        <span class="review-field-error" id="error-rating"></span>
                    </div>
                    
                    <!-- Titlu recenzie -->
                    <div class="review-field">
                        <label for="review-title"><?php _e('reviews_form_title'); ?> <span class="required">*</span></label>
                        <input type="text" id="review-title" name="title" placeholder="<?php _e('reviews_form_title_placeholder'); ?>" required maxlength="100">
                        <span class="review-field-counter"><span id="title-count">0</span>/100</span>
                        <span class="review-field-error" id="error-title"></span>
                    </div>
                    
                    <!-- Textul recenziei -->
                    <div class="review-field">
                        <label for="review-message"><?php _e('reviews_form_message'); ?> <span class="required">*</span></label>
                        <textarea id="review-message" name="message" rows="5" placeholder="<?php _e('reviews_form_message_placeholder'); ?>" required minlength="20" maxlength="1000"></textarea>
                        <span class="review-field-counter"><span id="message-count">0</span>/1000 (min. 20)</span>
                        <span class="review-field-error" id="error-message"></span>
                    </div>
                    
                    <!-- Termeni și condiții -->
                    <div class="review-field review-field-checkbox">
                        <label class="review-checkbox">
                            <input type="checkbox" id="review-terms" name="terms" required>
                            <span class="checkmark"></span>
                            <span><?php _e('reviews_form_terms'); ?> <span class="required">*</span></span>
                        </label>
                        <span class="review-field-error" id="error-terms"></span>
                    </div>
                    
                    <!-- Butoane -->
                    <div class="review-form-actions">
                        <button type="button" class="btn btn-secondary" id="cancelReview"><?php _e('reviews_form_cancel'); ?></button>
                        <button type="submit" class="btn btn-primary" id="submitReview">
                            <span class="btn-text"><?php _e('reviews_form_submit'); ?></span>
                            <span class="btn-loading" style="display:none;">
                                <svg class="spinner" width="20" height="20" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="31.4" stroke-dashoffset="10"/>
                                </svg>
                                <?php _e('form_sending'); ?>
                            </span>
                        </button>
                    </div>
                </form>
                
                <!-- Mesaj de succes (ascuns inițial) -->
                <div class="review-success" id="reviewSuccess" style="display:none;">
                    <div class="review-success-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <h4><?php _e('reviews_success_title'); ?></h4>
                    <p><?php _e('reviews_success_message'); ?></p>
                    <button type="button" class="btn btn-primary" id="closeSuccessModal"><?php _e('close'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ SECTION -->
    <section class="section faq" id="faq">
        <div class="container">
            <div class="section-header text-center fade-in">
                <span class="section-label"><?php echo e(t($faqSection, 'section_label') ?: __('faq_section_label')); ?></span>
                <h2 class="section-title"><?php echo e(t($faqSection, 'title')); ?></h2>
            </div>
            <div class="faq-container fade-in">
                <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <button class="faq-question">
                        <span><?php echo e(t($faq, 'question')); ?></span>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p><?php echo nl2br(e(t($faq, 'answer'))); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="section contact" id="contact">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info fade-in-left">
                    <span class="section-label"><?php echo e(t($contact, 'section_label') ?: __('contact_section_label')); ?></span>
                    <h2 class="section-title"><?php echo e(t($contact, 'title')); ?></h2>
                    <p class="contact-description"><?php echo e(t($contact, 'description')); ?></p>
                    
                    <div class="contact-details">
                        <?php if (!empty($contact['phone_enabled']) || !empty($office_raw)): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <?php echo !empty($contact['phone_icon']) ? $contact['phone_icon'] : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'; ?>
                            </div>
                            <div class="contact-text">
                                <h4><?php _e('contact_phone_label'); ?></h4>
                                <?php $phone_display = !empty($contact['phone']) ? $contact['phone'] : $office_raw; ?>
                                <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $phone_display); ?>"><?php echo e($phone_display); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact['whatsapp_enabled']) && (!empty($contact['whatsapp']) || !empty($office_raw))): ?>
                        <div class="contact-item">
                            <div class="contact-icon" style="color:#25D366; background-color: rgba(37,211,102,0.1);">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4>WhatsApp</h4>
                                <a href="https://wa.me/<?php echo $whatsapp_digits; ?>"><?php _e('contact_whatsapp_link'); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($contact['viber_enabled']) || !empty($viber_raw)): ?>
                        <div class="contact-item">
                            <div class="contact-icon" style="background-color: rgba(102,92,172,0.1);">
                                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/viber.svg" alt="Viber" width="22" height="22" style="filter: invert(32%) sepia(58%) saturate(563%) hue-rotate(214deg) brightness(89%) contrast(91%);"/>
                            </div>
                            <div class="contact-text">
                                <h4>Viber</h4>
                                <a href="viber://chat?number=%2B<?php echo $viber_digits; ?>"><?php _e('contact_viber_link'); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact['email_enabled']) || !empty($email_raw)): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <?php echo !empty($contact['email_icon']) ? $contact['email_icon'] : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>'; ?>
                            </div>
                            <div class="contact-text">
                                <h4><?php _e('contact_email_label'); ?></h4>
                                <a href="mailto:<?php echo e($email_raw); ?>"><?php echo e($email_raw); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact['address_enabled']) || !empty($contact['address'])): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <?php echo !empty($contact['address_icon']) ? $contact['address_icon'] : '<svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>'; ?>
                            </div>
                            <div class="contact-text">
                                <h4><?php _e('contact_address_label'); ?></h4>
                                <p><?php echo e($contact['address']); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="contact-map">
                        <?php if (!empty($contact['map_enabled']) && !empty($contact['map_embed'])): ?>
                            <?php 
                            // If map_embed is just a URL, wrap it in an iframe
                            $map_html = $contact['map_embed'];
                            if (strpos($map_html, '<iframe') === false && strpos($map_html, 'http') === 0) {
                                $map_html = '<iframe src="' . htmlspecialchars($map_html) . '" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
                            }
                            echo $map_html;
                            ?>
                        <?php else: ?>
                            <?php echo $map_default_iframe; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="contact-form-container fade-in-right">
                    <form class="contact-form" id="contactForm">
                        <input type="hidden" name="source" value="contact_form">
                        <h3 class="form-title"><?php echo e(t($contact, 'form_title') ?: __('contact_form_title')); ?></h3>
                        <div class="form-group">
                            <label for="contact-name"><?php _e('form_name'); ?> *</label>
                            <input type="text" id="contact-name" name="nume" required placeholder="<?php _e('form_name_placeholder'); ?>" maxlength="100">
                            <span class="form-error" id="error-contact-nume"></span>
                        </div>
                        <div class="form-group">
                            <label for="contact-service"><?php _e('form_service'); ?> *</label>
                            <select id="contact-service" name="serviciu" required>
                                <option value=""><?php _e('form_service_placeholder'); ?></option>
                                <?php foreach ($services as $service): ?>
                                <option value="<?php echo e(strtolower($service['title'])); ?>"><?php echo e(t($service, 'title')); ?></option>
                                <?php endforeach; ?>
                                <option value="altele"><?php _e('form_service_other'); ?></option>
                            </select>
                            <span class="form-error" id="error-contact-serviciu"></span>
                        </div>
                        <div class="form-group">
                            <label for="contact-phone"><?php _e('form_phone'); ?> *</label>
                            <div class="phone-input-group">
                                <select name="phone_prefix" class="phone-prefix-select" id="contact-prefix">
                                    <option value="+373"><?php _e('phone_prefix_md'); ?></option>
                                    <option value="+40"><?php _e('phone_prefix_ro'); ?></option>
                                    <option value="+380"><?php _e('phone_prefix_ua'); ?></option>
                                </select>
                                <input type="tel" id="contact-phone" name="telefon" required placeholder="<?php _e('form_phone_placeholder'); ?>" class="phone-number-input">
                            </div>
                            <span class="form-error" id="error-contact-telefon"></span>
                        </div>
                        <div class="form-group recaptcha-wrapper">
                            <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                            <span class="form-error" id="error-contact-recaptcha"></span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full"><?php _e('contact_form_submit'); ?></button>
                        <p class="form-note"><?php _e('contact_form_note'); ?></p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#" class="footer-logo">
                        <img src="/img/logo.png" alt="Cetățenia Română Logo" class="logo-img" style="height:120px;max-width:500px;object-fit:contain;"/>
                    </a>
                    <p class="footer-description"><?php _e('footer_description'); ?></p>
                </div>
                <div class="footer-links">
                    <h4><?php _e('footer_navigation'); ?></h4>
                    <ul>
                        <li><a href="#acasa"><?php _e('nav_home'); ?></a></li>
                        <li><a href="#despre"><?php _e('nav_about'); ?></a></li>
                        <li><a href="#servicii"><?php _e('nav_services'); ?></a></li>
                        <li><a href="#proces"><?php _e('nav_process'); ?></a></li>
                        <li><a href="#recenzii"><?php _e('nav_reviews'); ?></a></li>
                        <li><a href="#contact"><?php _e('nav_contact'); ?></a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4><?php _e('nav_services'); ?></h4>
                    <ul>
                        <?php 
                        $footerServices = array_slice($services, 0, 6); // Show only first 6 services
                        foreach ($footerServices as $service): ?>
                        <li><a href="#servicii"><?php echo e(t($service, 'title')); ?></a></li>
                        <?php endforeach; ?>
                        <?php if (count($services) > 6): ?>
                        <li><a href="#servicii" class="view-all-link"><?php _e('footer_view_all_services'); ?> &rarr;</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4><?php _e('nav_contact'); ?></h4>
                    <?php if (!empty($contact['phone']) || !empty($office_raw)): ?>
                    <?php $phone_display = !empty($contact['phone']) ? $contact['phone'] : $office_raw; ?>
                    <p><strong><?php _e('contact_phone_label'); ?></strong><?php echo e($phone_display); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($contact['email'])): ?>
                    <p><strong><?php _e('contact_email_label'); ?></strong><?php echo e($contact['email']); ?></p>
                    <?php else: ?>
                    <p><strong><?php _e('contact_email_label'); ?></strong>contact@acteromania.ro</p>
                    <?php endif; ?>
                    <?php if (!empty($contact['address']) || !empty($default_address)): ?>
                    <p><strong><?php _e('contact_address_label'); ?></strong><?php echo e(!empty($contact['address']) ? $contact['address'] : $default_address); ?></p>
                    <?php endif; ?>
                    <div class="footer-social">
                        <?php if (!empty($settings['facebook_url'])): ?>
                        <a href="<?php echo e($settings['facebook_url']); ?>" aria-label="Facebook" class="social-link" target="_blank">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['instagram_url'])): ?>
                        <a href="<?php echo e($settings['instagram_url']); ?>" aria-label="Instagram" class="social-link" target="_blank">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($whatsapp_digits)): ?>
                        <a href="https://wa.me/<?php echo $whatsapp_digits; ?>" aria-label="WhatsApp" class="social-link" target="_blank">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($viber_digits)): ?>
                        <a href="viber://chat?number=%2B<?php echo $viber_digits; ?>" aria-label="Viber" class="social-link" style="color:#665CAC">
                            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/viber.svg" alt="Viber" width="24" height="24" style="vertical-align:middle;filter:invert(1) grayscale(1) brightness(2);"/>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo e($settings['site_name'] ?? 'Cetățenia Română'); ?>. <?php _e('footer_rights'); ?></p>
                <div class="footer-legal">
                    <a href="#"><?php _e('footer_privacy'); ?></a>
                    <a href="#"><?php _e('footer_terms'); ?></a>
                </div>
            </div>
        </div>
    </footer>

    <?php /* Viber button removed per request */ ?>

    <!-- WhatsApp Floating Button -->
    <?php if (!empty($whatsapp_digits)): ?>
    <!-- Viber Floating Button (inline SVG) -->
    <?php if (!empty($viber_digits)): ?>
    <a href="https://viber.me/37378625057" id="viberFloatBtn" class="viber-float" aria-label="<?php _e('viber_open'); ?>" target="_blank" rel="noopener">
        <!-- Rounded-square Viber-like icon (purple background + white handset) -->
        <svg viewBox="0 0 24 24" width="30" height="30" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="0" width="24" height="24" rx="5" ry="5" fill="#665CAC" />
            <path d="M16.2 14.8c-.4 0-.8-.1-1.2-.3-.3-.2-.6-.3-.9-.1-.3.2-1 .6-1.7.3-.9-.4-2-1.4-3-2.4-1-1-2-2.1-2.4-3-.3-.7.1-1.4.3-1.7.2-.3 0-.6-.1-.9-.2-.4-.3-.8-.3-1.2C4.6 6 6.4 4.2 8.7 3.9c1-.1 2 .1 2.9.5 1 .4 2 1.4 3 2.4 1 1 2 2 2.4 3 .4.9.6 1.9.5 2.9-.3 2.3-2.1 4.1-4.4 4.4-.1 0-.2.1-.2.1z" fill="#fff" transform="translate(-0.5,-0.5) scale(1.05)"/>
        </svg>
    </a>
    <?php endif; ?>
    <button id="whatsappFloatBtn" class="whatsapp-float" aria-label="<?php _e('whatsapp_open'); ?>">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </button>

    <div id="whatsappCard" class="whatsapp-card" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="whatsapp-card-inner">
            <button class="whatsapp-card-close" aria-label="<?php _e('close'); ?>">×</button>
            <h4 class="whatsapp-card-title"><?php _e('whatsapp_title'); ?></h4>
            <p class="whatsapp-card-sub"><?php _e('whatsapp_subtitle'); ?></p>
            <ul class="whatsapp-contacts">
                <li class="contact-item">
                    <a href="https://wa.me/<?php echo $whatsapp_digits; ?>" target="_blank" rel="noopener noreferrer">
                        <span class="contact-label">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;margin-right:8px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                            <?php _e('whatsapp_office'); ?>
                        </span>
                        <span class="contact-number"><?php echo $office_raw; ?></span>
                    </a>
                </li>
                <li class="contact-item">
                    <?php $second = '+37378625057'; ?>
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/','', $second); ?>" target="_blank" rel="noopener noreferrer">
                        <span class="contact-label">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;margin-right:8px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                            Trofim
                        </span>
                        <span class="contact-number"><?php echo $second; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('reviewForm');
        if (!form) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            var submitBtn = document.getElementById('submitReview');
            var btnText = submitBtn.querySelector('.btn-text');
            var btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            submitBtn.disabled = true;
            if (btnText) btnText.style.display = 'none';
            if (btnLoading) btnLoading.style.display = 'inline-block';

            // Clear previous field errors
            ['name','email','rating','title','message','terms','csrf'].forEach(function(f){
                var el = document.getElementById('error-' + f);
                if (el) el.textContent = '';
            });

            // Collect form data
            var fd = new FormData(form);
            var data = {};
            fd.forEach(function(value, key) {
                if (key === 'terms') {
                    data[key] = form.querySelector('[name="terms"]').checked;
                } else {
                    data[key] = value;
                }
            });

            try {
                var resp = await fetch('reviews/submit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                var json = await resp.json();

                if (resp.ok && json.success) {
                    // Show success preview and reset
                    var successEl = document.getElementById('reviewSuccess');
                    if (successEl) {
                        // Update message to indicate awaiting confirmation
                        var p = successEl.querySelector('p');
                        if (p) p.textContent = window.TRANSLATIONS && window.TRANSLATIONS.reviews_sent_confirmation || 'Review submitted successfully.';
                        successEl.style.display = 'block';
                    }
                    form.reset();

                    // Show a small toast/card to confirm submission and awaiting confirmation
                    if (window.showReviewSentCard) {
                        try { window.showReviewSentCard(window.TRANSLATIONS && window.TRANSLATIONS.reviews_sent_confirmation || 'Review submitted.'); } catch (e) { console.log('showReviewSentCard error', e); }
                    }

                    // Close modal immediately (fix bug: modal stayed visible for a delay)
                    (function closeModalImmediate(){
                        var modal = document.getElementById('reviewModal');
                        if (modal) {
                            modal.classList.remove('active');
                            modal.classList.remove('closing');
                            modal.setAttribute('aria-hidden', 'true');
                        }
                        if (successEl) successEl.style.display = 'none';
                        document.body.style.overflow = '';
                    })();
                } else {
                    // Show field-specific error if provided
                    if (json.field) {
                        var fieldEl = document.getElementById('error-' + json.field);
                        if (fieldEl) fieldEl.textContent = json.error || (window.TRANSLATIONS && window.TRANSLATIONS.error_message || 'Error');
                        else alert(json.error || (window.TRANSLATIONS && window.TRANSLATIONS.form_error || 'Error'));
                    } else {
                        alert(json.error || (window.TRANSLATIONS && window.TRANSLATIONS.form_error || 'Error'));
                    }
                }
            } catch (err) {
                alert((window.TRANSLATIONS && window.TRANSLATIONS.form_error || 'Error') + ': ' + err.message);
            } finally {
                // Restore button state
                submitBtn.disabled = false;
                if (btnText) btnText.style.display = '';
                if (btnLoading) btnLoading.style.display = 'none';
            }
        });
    });
    </script>

    <script src="js/main.js"></script>
    <script>
    // Fallback simplu pentru deschiderea/închiderea modalului de recenzie
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('openReviewModal');
        var modal = document.getElementById('reviewModal');
        var backdrop = document.getElementById('reviewModalBackdrop');
        var closeBtn = document.getElementById('closeReviewModal');
        var cancelBtn = document.getElementById('cancelReview');

        if (!btn || !modal) return;

        function openModal() {
            // ensure any previous closing state removed
            modal.classList.remove('closing');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            // Anti-spam: setează timestamp la deschiderea modalului
            var ftField = document.getElementById('review-ft');
            if (ftField) ftField.value = Math.floor(Date.now() / 1000);
        }

        function closeModal() {
            // gentle close: add closing class then remove active after transition
            modal.classList.add('closing');
            modal.setAttribute('aria-hidden', 'true');
            setTimeout(function(){
                modal.classList.remove('active');
                modal.classList.remove('closing');
                document.body.style.overflow = '';
            }, 380);
        }

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });

        [backdrop, closeBtn, cancelBtn].forEach(function(el) {
            if (!el) return;
            el.addEventListener('click', function(e) {
                e.preventDefault();
                closeModal();
            });
        });
    });
    </script>
</body>
</html>
