<?php
/**
 * ActeRomânia CMS - Contacts History Manager
 * Gestionează istoricul contactelor primite de pe site
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Istoric Contacte';
$currentPage = 'contacts';

// Pagination
$perPage = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Filter by source
$sourceFilter = $_GET['source'] ?? 'all';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: contacts.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete_contact') {
        try {
            $stmt = $pdo->prepare("DELETE FROM contacts_history WHERE id = ?");
            $stmt->execute([(int)$_POST['contact_id']]);
            $_SESSION['flash_message'] = 'Contact șters!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_all') {
        try {
            $pdo->exec("DELETE FROM contacts_history");
            $_SESSION['flash_message'] = 'Toate contactele au fost șterse!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: contacts.php');
    exit;
}

// Count total for pagination
$whereClause = '';
$params = [];
if ($sourceFilter !== 'all') {
    $whereClause = 'WHERE source = ?';
    $params[] = $sourceFilter;
}

$totalCount = $pdo->prepare("SELECT COUNT(*) FROM contacts_history $whereClause");
$totalCount->execute($params);
$total = $totalCount->fetchColumn();
$totalPages = ceil($total / $perPage);

// Count by source for tabs
$consultationCount = $pdo->query("SELECT COUNT(*) FROM contacts_history WHERE source = 'consultation_form'")->fetchColumn();
$contactCount = $pdo->query("SELECT COUNT(*) FROM contacts_history WHERE source = 'contact_form'")->fetchColumn();

// Get contacts
$sql = "SELECT * FROM contacts_history $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="section-header">
    <div class="section-header-content">
        <p>Vizualizați toate solicitările primite de pe site</p>
    </div>
    <?php if ($total > 0): ?>
    <form method="POST" style="display: inline;" onsubmit="return confirm('Sigur doriți să ștergeți toate contactele?');">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="action" value="delete_all">
        <button type="submit" class="btn btn-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            Șterge Tot
        </button>
    </form>
    <?php endif; ?>
</div>

<!-- Filter Tabs -->
<div class="tabs-container" style="margin-bottom: 1.5rem;">
    <a href="?source=all" class="tab-btn <?php echo $sourceFilter === 'all' ? 'active' : ''; ?>">
        Toate (<?php echo $total; ?>)
    </a>
    <a href="?source=consultation_form" class="tab-btn <?php echo $sourceFilter === 'consultation_form' ? 'active' : ''; ?>">
        Consultație (<?php echo $consultationCount; ?>)
    </a>
    <a href="?source=contact_form" class="tab-btn <?php echo $sourceFilter === 'contact_form' ? 'active' : ''; ?>">
        Contact (<?php echo $contactCount; ?>)
    </a>
</div>

<?php if (empty($contacts)): ?>
<div class="empty-state">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="64" height="64" style="margin: 0 auto 1rem; display: block; opacity: 0.5;">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
    </svg>
    <h3>Niciun contact</h3>
    <p>Nu există contacte înregistrate<?php echo $sourceFilter !== 'all' ? ' pentru acest filtru' : ''; ?>.</p>
</div>
<?php else: ?>

<div class="contacts-grid">
    <?php foreach ($contacts as $contact): ?>
    <div class="contact-card">
        <div class="contact-card-header">
            <div>
                <div class="contact-card-name"><?php echo htmlspecialchars($contact['name']); ?></div>
                <div class="contact-card-date">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="display: inline; vertical-align: middle; margin-right: 4px;">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <?php echo date('d.m.Y H:i', strtotime($contact['created_at'])); ?>
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <?php 
                $sourceLabel = $contact['source'] === 'consultation_form' ? 'Consultație' : 'Contact';
                $sourceClass = $contact['source'] === 'consultation_form' ? 'badge-primary' : 'badge-secondary';
                ?>
                <span class="badge <?php echo $sourceClass; ?>"><?php echo $sourceLabel; ?></span>
            </div>
        </div>
        <div class="contact-card-body">
            <div class="contact-card-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <a href="tel:<?php echo preg_replace('/[^+0-9]/', '', $contact['phone']); ?>" style="color: var(--admin-primary); font-weight: 500;">
                    <?php echo htmlspecialchars($contact['phone']); ?>
                </a>
            </div>
            <?php if (!empty($contact['service'])): ?>
            <div class="contact-card-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                <span class="badge badge-info"><?php echo htmlspecialchars($contact['service']); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <div class="contact-card-footer">
            <small class="text-muted">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="display: inline; vertical-align: middle; margin-right: 4px;">
                    <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/>
                    <line x1="7" y1="2" x2="7" y2="22"/>
                    <line x1="17" y1="2" x2="17" y2="22"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <line x1="2" y1="7" x2="7" y2="7"/>
                    <line x1="2" y1="17" x2="7" y2="17"/>
                    <line x1="17" y1="17" x2="22" y2="17"/>
                    <line x1="17" y1="7" x2="22" y2="7"/>
                </svg>
                IP: <?php echo htmlspecialchars($contact['ip_address'] ?? '-'); ?>
            </small>
            <form method="POST" style="display: inline;" onsubmit="return confirm('Sigur doriți să ștergeți acest contact?');">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="delete_contact">
                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                <button type="submit" class="btn btn-sm btn-danger" title="Șterge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                    Șterge
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>&source=<?php echo $sourceFilter; ?>" class="btn btn-sm btn-outline">&laquo; Anterior</a>
    <?php endif; ?>
    
    <span class="page-info">Pagina <?php echo $page; ?> din <?php echo $totalPages; ?></span>
    
    <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo $page + 1; ?>&source=<?php echo $sourceFilter; ?>" class="btn btn-sm btn-outline">Următor &raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php endif; ?>

<?php include '../includes/footer.php'; ?>
