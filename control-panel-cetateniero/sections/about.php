<?php
/**
 * ActeRomânia CMS - About Section Manager
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Despre Noi';
$currentPage = 'about';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: about.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_about') {
        try {
            // Handle image upload
            $imagePath = $_POST['current_image'] ?? '';
            if (!empty($_FILES['image']['name'])) {
                $uploaded = uploadImage($_FILES['image'], 'about');
                if ($uploaded) {
                    if ($imagePath) deleteImage($imagePath);
                    $imagePath = $uploaded;
                }
            }
            
            // Insert row if missing, otherwise update
            $exists = (int)$pdo->query("SELECT COUNT(*) FROM about WHERE id = 1")->fetchColumn();
            if ($exists === 0) {
                $stmt = $pdo->prepare("INSERT INTO about (id, section_label, title, content, image_url, counters_enabled, created_at, updated_at) VALUES (1, ?, ?, ?, ?, 1, NOW(), NOW())");
                $stmt->execute([
                    sanitizeInput($_POST['subtitle']),
                    sanitizeInput($_POST['title']),
                    $_POST['description'], // Allow HTML
                    $imagePath
                ]);
            } else {
                $stmt = $pdo->prepare("UPDATE about SET title = ?, section_label = ?, content = ?, image_url = ?, updated_at = NOW() WHERE id = 1");
                
                $stmt->execute([
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['subtitle']),
                    $_POST['description'], // Allow HTML
                    $imagePath
                ]);
            }
            
            $_SESSION['flash_message'] = 'Secțiunea Despre Noi actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'add_stat') {
        try {
            $stmt = $pdo->prepare("INSERT INTO about_stats (number_value, suffix, label, sort_order) VALUES (?, ?, ?, ?)");
            $order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM about_stats")->fetchColumn();
            $stmt->execute([
                (int)$_POST['number'],
                sanitizeInput($_POST['suffix']),
                sanitizeInput($_POST['label']),
                $order
            ]);
            $_SESSION['flash_message'] = 'Statistică adăugată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'update_stat') {
        try {
            $stmt = $pdo->prepare("UPDATE about_stats SET number_value = ?, suffix = ?, label = ? WHERE id = ?");
            $stmt->execute([
                (int)$_POST['number'],
                sanitizeInput($_POST['suffix']),
                sanitizeInput($_POST['label']),
                (int)$_POST['item_id']
            ]);
            $_SESSION['flash_message'] = 'Statistică actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_stat') {
        try {
            $stmt = $pdo->prepare("DELETE FROM about_stats WHERE id = ?");
            $stmt->execute([(int)$_POST['item_id']]);
            $_SESSION['flash_message'] = 'Statistică ștearsă!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: about.php');
    exit;
}

// Get about data
$about = $pdo->query("SELECT * FROM about WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$stats = $pdo->query("SELECT * FROM about_stats ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="content-card">
    <div class="card-header">
        <h2>Editare Secțiune Despre Noi</h2>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="update_about">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($about['image_url'] ?? ''); ?>">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Titlu Secțiune</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($about['title'] ?? ''); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="subtitle">Etichetă Secțiune</label>
                    <input type="text" id="subtitle" name="subtitle" class="form-control" 
                           value="<?php echo htmlspecialchars($about['section_label'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Conținut</label>
                <textarea id="description" name="description" class="form-control" rows="6"><?php echo htmlspecialchars($about['content'] ?? ''); ?></textarea>
                <small class="form-text">Puteți folosi tag-uri HTML de bază (p, strong, em, br)</small>
            </div>
            
            <div class="form-group">
                <label>Imagine</label>
                <div class="image-upload-area">
                    <div class="image-preview <?php echo !empty($about['image_url']) ? 'has-image' : ''; ?>" id="aboutImagePreview">
                        <?php if (!empty($about['image_url'])): ?>
                            <img src="<?php echo SITE_URL . '/' . $about['image_url']; ?>" alt="About Image">
                        <?php else: ?>
                            <div class="upload-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <span>Click pentru a încărca imaginea</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="image" id="aboutImage" accept="image/*" 
                           onchange="previewImage(this, 'aboutImagePreview')">
                    <small class="form-text">Recomandare: 600x600px, format JPG/PNG</small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Salvează Modificările
                </button>
            </div>
        </form>
    </div>
</div>

<div class="content-card mt-4">
    <div class="card-header">
        <h2>Statistici (Contoare Animate)</h2>
        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('addStatModal').style.display='flex'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Adaugă Statistică
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($stats)): ?>
            <div class="empty-state">
                <p>Nu există statistici. Adăugați prima statistică.</p>
            </div>
        <?php else: ?>
            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stat['number_value'] . $stat['suffix']; ?></div>
                    <div class="stat-label"><?php echo htmlspecialchars($stat['label']); ?></div>
                    <div class="stat-actions">
                        <button type="button" class="btn btn-sm btn-outline" 
                                onclick="editStat(<?php echo $stat['id']; ?>, <?php echo $stat['number_value']; ?>, '<?php echo addslashes($stat['suffix']); ?>', '<?php echo addslashes($stat['label']); ?>');">
                            Editează
                        </button>
                        <form method="POST" style="display:inline" onsubmit="return confirmDelete()">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="delete_stat">
                            <input type="hidden" name="item_id" value="<?php echo $stat['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline btn-danger">Șterge</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Stat Modal -->
<div class="modal" id="addStatModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Adaugă Statistică</h3>
            <button type="button" class="modal-close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="add_stat">
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="add_number">Număr</label>
                        <input type="number" id="add_number" name="number" class="form-control" placeholder="15" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="add_suffix">Sufix</label>
                        <input type="text" id="add_suffix" name="suffix" class="form-control" placeholder="+" maxlength="10">
                        <small class="form-text">Ex: +, %, k</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="add_label">Etichetă</label>
                    <input type="text" id="add_label" name="label" class="form-control" placeholder="Ani de Experiență" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="this.closest('.modal').style.display='none'">Anulează</button>
                <button type="submit" class="btn btn-primary">Adaugă</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Stat Modal -->
<div class="modal" id="editStatModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editează Statistică</h3>
            <button type="button" class="modal-close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_stat">
                <input type="hidden" name="item_id" id="edit_stat_id">
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="edit_number">Număr</label>
                        <input type="number" id="edit_number" name="number" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="edit_suffix">Sufix</label>
                        <input type="text" id="edit_suffix" name="suffix" class="form-control" maxlength="10">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_label">Etichetă</label>
                    <input type="text" id="edit_label" name="label" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="this.closest('.modal').style.display='none'">Anulează</button>
                <button type="submit" class="btn btn-primary">Salvează</button>
            </div>
        </form>
    </div>
</div>

<script>
function editStat(id, number, suffix, label) {
    document.getElementById('edit_stat_id').value = id;
    document.getElementById('edit_number').value = number;
    document.getElementById('edit_suffix').value = suffix;
    document.getElementById('edit_label').value = label;
    document.getElementById('editStatModal').style.display = 'flex';
}

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>

<style>
.form-row { display: flex; gap: 1.5rem; margin-bottom: 1rem; }
.form-row .form-group { flex: 1; margin-bottom: 0; }
.col-md-6 { flex: 0 0 50%; }
.mt-4 { margin-top: 2rem; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}
.stat-card {
    background: linear-gradient(135deg, #1a365d, #2d4a7c);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    display: flex;
    flex-direction: column;
    min-height: 180px;
}
.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #c9a227;
}
.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-top: 0.5rem;
    flex-grow: 1;
}
.stat-actions {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(255,255,255,0.2);
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}
.stat-actions .btn { 
    background: rgba(255,255,255,0.9);
    color: #1a365d; 
    border: none;
    font-size: 0.75rem;
    padding: 0.35rem 0.85rem;
    font-weight: 500;
}
.stat-actions .btn:hover { 
    background: white; 
}
.stat-actions .btn-danger {
    background: #ef4444;
    color: white;
}
.stat-actions .btn-danger:hover {
    background: #dc2626;
}

.modal {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}
.modal-header h3 { margin: 0; }
.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #64748b;
}
.modal-body { padding: 1.5rem; }
.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #64748b;
}

@media (max-width: 768px) {
    .form-row { flex-direction: column; gap: 0; }
    .col-md-6 { flex: 0 0 100%; }
}
</style>

<?php include '../includes/footer.php'; ?>
