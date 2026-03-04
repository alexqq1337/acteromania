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
        <h2>Istoric Contacte</h2>
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

<div class="card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Nume</th>
                    <th>Telefon</th>
                    <th>Serviciu</th>
                    <th>Sursă</th>
                    <th>IP</th>
                    <th style="width: 80px;">Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td>
                        <span class="text-muted">
                            <?php echo date('d.m.Y', strtotime($contact['created_at'])); ?>
                        </span><br>
                        <small><?php echo date('H:i', strtotime($contact['created_at'])); ?></small>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($contact['name']); ?></strong>
                    </td>
                    <td>
                        <a href="tel:<?php echo preg_replace('/[^+0-9]/', '', $contact['phone']); ?>" style="color: inherit; text-decoration: none;">
                            <?php echo htmlspecialchars($contact['phone']); ?>
                        </a>
                    </td>
                    <td>
                        <?php if (!empty($contact['service'])): ?>
                        <span class="badge badge-info"><?php echo htmlspecialchars($contact['service']); ?></span>
                        <?php else: ?>
                        <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        $sourceLabel = $contact['source'] === 'consultation_form' ? 'Consultație' : 'Contact';
                        $sourceClass = $contact['source'] === 'consultation_form' ? 'badge-primary' : 'badge-secondary';
                        ?>
                        <span class="badge <?php echo $sourceClass; ?>"><?php echo $sourceLabel; ?></span>
                    </td>
                    <td>
                        <small class="text-muted"><?php echo htmlspecialchars($contact['ip_address'] ?? '-'); ?></small>
                    </td>
                    <td>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Sigur doriți să ștergeți acest contact?');">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="delete_contact">
                            <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-icon btn-danger" title="Șterge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination" style="margin-top: 1.5rem; display: flex; gap: 0.5rem; justify-content: center;">
    <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>&source=<?php echo $sourceFilter; ?>" class="btn btn-sm btn-outline">&laquo; Anterior</a>
    <?php endif; ?>
    
    <span class="btn btn-sm" style="background: var(--bg-secondary); pointer-events: none;">
        Pagina <?php echo $page; ?> din <?php echo $totalPages; ?>
    </span>
    
    <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo $page + 1; ?>&source=<?php echo $sourceFilter; ?>" class="btn btn-sm btn-outline">Următor &raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php endif; ?>

<style>
.tabs-container {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.tab-btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    background: var(--bg-secondary);
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s;
}
.tab-btn:hover {
    background: var(--bg-hover);
}
.tab-btn.active {
    background: var(--primary-color);
    color: white;
}
.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}
.badge-info {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}
.badge-primary {
    background: rgba(201, 162, 39, 0.15);
    color: #c9a227;
}
.badge-secondary {
    background: rgba(100, 116, 139, 0.15);
    color: #64748b;
}
.empty-state {
    text-align: center;
    padding: 3rem;
    background: var(--bg-secondary);
    border-radius: 8px;
}
.empty-state h3 {
    margin-bottom: 0.5rem;
}
.empty-state p {
    color: var(--text-secondary);
}
.btn-icon {
    padding: 0.4rem;
    min-width: auto;
}
</style>

<?php include '../includes/footer.php'; ?>
