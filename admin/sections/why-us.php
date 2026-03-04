<?php
/**
 * ActeRomânia CMS - Why Us Section Manager
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'De Ce Noi';
$currentPage = 'why-us';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: why-us.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_section') {
        try {
            // Handle image upload
            $imagePath = $_POST['current_image'] ?? '';
            if (!empty($_FILES['image']['name'])) {
                $uploaded = uploadImage($_FILES['image'], 'why-us');
                if ($uploaded) {
                    if ($imagePath && strpos($imagePath, 'uploads/') !== false) {
                        deleteImage($imagePath);
                    }
                    $imagePath = $uploaded;
                }
            }
            
            $stmt = $pdo->prepare("UPDATE why_us SET title = ?, image_url = ?, updated_at = NOW() WHERE id = 1");
            $stmt->execute([sanitizeInput($_POST['title']), $imagePath]);
            $_SESSION['flash_message'] = 'Secțiunea actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'add_item') {
        try {
            $stmt = $pdo->prepare("INSERT INTO why_us_items (icon_svg, title, description, sort_order) VALUES (?, ?, ?, ?)");
            $order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM why_us_items")->fetchColumn();
            $stmt->execute([
                sanitizeInput($_POST['icon']),
                sanitizeInput($_POST['title']),
                sanitizeInput($_POST['description']),
                $order
            ]);
            $_SESSION['flash_message'] = 'Element adăugat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'update_item') {
        try {
            $stmt = $pdo->prepare("UPDATE why_us_items SET icon_svg = ?, title = ?, description = ? WHERE id = ?");
            $stmt->execute([
                sanitizeInput($_POST['icon']),
                sanitizeInput($_POST['title']),
                sanitizeInput($_POST['description']),
                (int)$_POST['item_id']
            ]);
            $_SESSION['flash_message'] = 'Element actualizat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_item') {
        try {
            $stmt = $pdo->prepare("DELETE FROM why_us_items WHERE id = ?");
            $stmt->execute([(int)$_POST['item_id']]);
            $_SESSION['flash_message'] = 'Element șters!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: why-us.php');
    exit;
}

// Get data
$section = $pdo->query("SELECT * FROM why_us WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$items = $pdo->query("SELECT * FROM why_us_items ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="content-card">
    <div class="card-header">
        <h2>Setări Secțiune</h2>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="update_section">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($section['image_url'] ?? ''); ?>">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Titlu Secțiune</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($section['title'] ?? 'De Ce Să Ne Alegeți'); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="image">Imagine Secțiune</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">JPG, PNG, GIF, WebP (max 5MB)</small>
                </div>
            </div>
            
            <?php if (!empty($section['image_url'])): ?>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Imagine Curentă</label>
                    <div class="current-image-preview" style="max-width: 300px;">
                        <img src="<?php echo htmlspecialchars($section['image_url']); ?>" alt="Imagine curentă" style="width: 100%; border-radius: 8px;">
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvează</button>
            </div>
        </form>
    </div>
</div>

<div class="content-card mt-4">
    <div class="card-header">
        <h2>Avantaje</h2>
        <button type="button" class="btn btn-sm btn-primary" onclick="openItemModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Adaugă Avantaj
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($items)): ?>
            <div class="empty-state"><p>Nu există avantaje. Adăugați primul avantaj.</p></div>
        <?php else: ?>
            <div class="advantages-grid">
                <?php foreach ($items as $item): ?>
                <div class="advantage-card">
                    <div class="advantage-icon"><?php echo html_entity_decode($item['icon_svg']); ?></div>
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                    <div class="advantage-actions">
                        <button type="button" class="btn btn-sm btn-outline" 
                                onclick="editItem(<?php echo htmlspecialchars(json_encode(array_merge($item, ['icon_svg' => html_entity_decode($item['icon_svg'])]))); ?>)">Editează</button>
                        <form method="POST" style="display:inline" onsubmit="return confirmDelete()">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="delete_item">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline btn-danger">Șterge</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Item Modal -->
<div class="modal" id="itemModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="itemModalTitle">Adaugă Avantaj</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" id="itemForm">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" id="itemAction" value="add_item">
                <input type="hidden" name="item_id" id="itemId">
                
                <div class="form-group">
                    <label for="item_icon">Icon (SVG sau Emoji)</label>
                    <textarea id="item_icon" name="icon" class="form-control" rows="3" 
                              placeholder="Cod SVG sau emoji" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="item_title">Titlu</label>
                    <input type="text" id="item_title" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="item_description">Descriere</label>
                    <textarea id="item_description" name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Anulează</button>
                <button type="submit" class="btn btn-primary">Salvează</button>
            </div>
        </form>
    </div>
</div>

<script>
function openItemModal() {
    document.getElementById('itemModalTitle').textContent = 'Adaugă Avantaj';
    document.getElementById('itemAction').value = 'add_item';
    document.getElementById('itemForm').reset();
    document.getElementById('itemModal').style.display = 'flex';
}

function editItem(item) {
    document.getElementById('itemModalTitle').textContent = 'Editează Avantaj';
    document.getElementById('itemAction').value = 'update_item';
    document.getElementById('itemId').value = item.id;
    document.getElementById('item_icon').value = item.icon_svg || '';
    document.getElementById('item_title').value = item.title;
    document.getElementById('item_description').value = item.description || '';
    document.getElementById('itemModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('itemModal').style.display = 'none';
}

document.getElementById('itemModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

<style>
.form-row { display: flex; gap: 1.5rem; margin-bottom: 1rem; }
.form-row .form-group { flex: 1; margin-bottom: 0; }
.col-md-6 { flex: 0 0 calc(50% - 0.75rem); }
.mt-4 { margin-top: 2rem; }

.advantages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.advantage-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
}

.advantage-icon {
    width: 50px;
    height: 50px;
    margin-bottom: 1rem;
    color: #1a365d;
}
.advantage-icon svg { width: 100%; height: 100%; }

.advantage-card h4 {
    margin: 0 0 0.5rem 0;
    color: #1a365d;
}

.advantage-card p {
    color: #64748b;
    font-size: 0.875rem;
    margin: 0 0 1rem 0;
}

.advantage-actions {
    display: flex;
    gap: 0.5rem;
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
