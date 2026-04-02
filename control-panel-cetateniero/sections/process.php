<?php
/**
 * ActeRomânia CMS - Process Section Manager
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Cum Funcționează';
$currentPage = 'process';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: process.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_section') {
        try {
            $stmt = $pdo->prepare("UPDATE process_section SET title = ?, description = ?, updated_at = NOW() WHERE id = 1");
            $stmt->execute([sanitizeInput($_POST['title']), sanitizeInput($_POST['description'])]);
            $_SESSION['flash_message'] = 'Secțiunea actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'add_step') {
        try {
            $imagePath = '';
            if (!empty($_FILES['image']['name'])) {
                $imagePath = uploadImage($_FILES['image'], 'process');
            }
            
            $stmt = $pdo->prepare("INSERT INTO process_steps (step_number, title, description, image_url, features, sort_order) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM process_steps")->fetchColumn();
            $stmt->execute([
                (int)$_POST['step_number'],
                sanitizeInput($_POST['title']),
                $_POST['description'],
                $imagePath,
                $_POST['features'],
                $order
            ]);
            $_SESSION['flash_message'] = 'Pas adăugat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'update_step') {
        try {
            $imagePath = $_POST['current_image'] ?? '';
            if (!empty($_FILES['image']['name'])) {
                $uploaded = uploadImage($_FILES['image'], 'process');
                if ($uploaded) {
                    if ($imagePath) deleteImage($imagePath);
                    $imagePath = $uploaded;
                }
            }
            
            $stmt = $pdo->prepare("UPDATE process_steps SET 
                step_number = ?, title = ?, description = ?, image_url = ?, features = ?, updated_at = NOW() 
                WHERE id = ?");
            $stmt->execute([
                (int)$_POST['step_number'],
                sanitizeInput($_POST['title']),
                $_POST['description'],
                $imagePath,
                $_POST['features'],
                (int)$_POST['step_id']
            ]);
            $_SESSION['flash_message'] = 'Pas actualizat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_step') {
        try {
            $stmt = $pdo->prepare("SELECT image_url FROM process_steps WHERE id = ?");
            $stmt->execute([(int)$_POST['step_id']]);
            $step = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($step && $step['image_url']) deleteImage($step['image_url']);
            
            $stmt = $pdo->prepare("DELETE FROM process_steps WHERE id = ?");
            $stmt->execute([(int)$_POST['step_id']]);
            $_SESSION['flash_message'] = 'Pas șters!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: process.php');
    exit;
}

// Get data
$section = $pdo->query("SELECT * FROM process_section WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$steps = $pdo->query("SELECT * FROM process_steps ORDER BY step_number ASC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="content-card">
    <div class="card-header">
        <h2>Setări Secțiune</h2>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="update_section">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Titlu Secțiune</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($section['title'] ?? 'Cum Funcționează'); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="description">Descriere</label>
                    <input type="text" id="description" name="description" class="form-control" 
                           value="<?php echo htmlspecialchars($section['description'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvează Setările</button>
            </div>
        </form>
    </div>
</div>

<div class="content-card mt-4">
    <div class="card-header">
        <h2>Pașii Procesului</h2>
        <button type="button" class="btn btn-sm btn-primary" onclick="openStepModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Adaugă Pas
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($steps)): ?>
            <div class="empty-state">
                <p>Nu există pași definiți. Adăugați primul pas al procesului.</p>
            </div>
        <?php else: ?>
            <div class="steps-timeline">
                <?php foreach ($steps as $step): ?>
                <div class="timeline-item" data-id="<?php echo $step['id']; ?>">
                    <div class="timeline-number"><?php echo $step['step_number']; ?></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h4><?php echo htmlspecialchars($step['title']); ?></h4>
                            <div class="timeline-actions">
                                <button type="button" class="btn btn-sm btn-outline" 
                                        onclick="editStep(<?php echo htmlspecialchars(json_encode($step)); ?>)">
                                    Editează
                                </button>
                                <form method="POST" style="display:inline" onsubmit="return confirmDelete()">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="delete_step">
                                    <input type="hidden" name="step_id" value="<?php echo $step['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline btn-danger">Șterge</button>
                                </form>
                            </div>
                        </div>
                        <p class="timeline-desc"><?php echo htmlspecialchars(substr($step['description'], 0, 150)); ?>...</p>
                        <?php if ($step['image_url']): ?>
                            <img src="<?php echo SITE_URL . '/' . $step['image_url']; ?>" alt="" class="timeline-thumb">
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Step Modal -->
<div class="modal" id="stepModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3 id="stepModalTitle">Adaugă Pas</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="stepForm">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" id="stepAction" value="add_step">
                <input type="hidden" name="step_id" id="stepId">
                <input type="hidden" name="current_image" id="currentImage">
                
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="step_number">Nr. Pas</label>
                        <input type="number" id="step_number" name="step_number" class="form-control" 
                               min="1" max="10" value="<?php echo count($steps) + 1; ?>" required>
                    </div>
                    <div class="form-group col-md-9">
                        <label for="step_title">Titlu</label>
                        <input type="text" id="step_title" name="title" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="step_description">Descriere</label>
                    <textarea id="step_description" name="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="step_features">Caracteristici (una pe linie)</label>
                    <textarea id="step_features" name="features" class="form-control" rows="4" 
                              placeholder="Caracteristica 1&#10;Caracteristica 2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Imagine</label>
                    <div class="image-upload-area">
                        <div class="image-preview" id="stepImagePreview">
                            <div class="upload-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <span>Click pentru imagine</span>
                            </div>
                        </div>
                        <input type="file" name="image" id="stepImage" accept="image/*" 
                               onchange="previewImage(this, 'stepImagePreview')">
                    </div>
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
function openStepModal() {
    document.getElementById('stepModalTitle').textContent = 'Adaugă Pas';
    document.getElementById('stepAction').value = 'add_step';
    document.getElementById('stepForm').reset();
    document.getElementById('step_number').value = <?php echo count($steps) + 1; ?>;
    resetImagePreview('stepImagePreview');
    document.getElementById('stepModal').style.display = 'flex';
}

function editStep(step) {
    document.getElementById('stepModalTitle').textContent = 'Editează Pas';
    document.getElementById('stepAction').value = 'update_step';
    document.getElementById('stepId').value = step.id;
    document.getElementById('currentImage').value = step.image_url || '';
    document.getElementById('step_number').value = step.step_number;
    document.getElementById('step_title').value = step.title;
    document.getElementById('step_description').value = step.description || '';
    document.getElementById('step_features').value = step.features || '';
    
    if (step.image_url) {
        document.getElementById('stepImagePreview').innerHTML = 
            '<img src="<?php echo SITE_URL; ?>/' + step.image_url + '" alt="Preview">';
        document.getElementById('stepImagePreview').classList.add('has-image');
    } else {
        resetImagePreview('stepImagePreview');
    }
    
    document.getElementById('stepModal').style.display = 'flex';
}

function resetImagePreview(id) {
    const preview = document.getElementById(id);
    preview.innerHTML = `<div class="upload-placeholder">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
        </svg>
        <span>Click pentru imagine</span>
    </div>`;
    preview.classList.remove('has-image');
}

function closeModal() {
    document.getElementById('stepModal').style.display = 'none';
}

document.getElementById('stepModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

<style>
.form-row { display: flex; gap: 1.5rem; margin-bottom: 1rem; }
.form-row .form-group { flex: 1; margin-bottom: 0; }
.col-md-3 { flex: 0 0 25%; }
.col-md-6 { flex: 0 0 calc(50% - 0.75rem); }
.col-md-9 { flex: 0 0 75%; }
.mt-4 { margin-top: 2rem; }

.steps-timeline {
    position: relative;
    padding-left: 60px;
}
.steps-timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #1a365d, #c9a227);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e2e8f0;
}
.timeline-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.timeline-number {
    position: absolute;
    left: -60px;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #1a365d, #2d4a7c);
    color: #c9a227;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    z-index: 1;
}

.timeline-content {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}
.timeline-header h4 {
    margin: 0;
    color: #1a365d;
}

.timeline-actions {
    display: flex;
    gap: 0.5rem;
}

.timeline-desc {
    color: #64748b;
    margin: 0 0 1rem 0;
    font-size: 0.875rem;
}

.timeline-thumb {
    max-width: 200px;
    height: auto;
    border-radius: 8px;
}

.modal {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
}
.modal-content {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.modal-lg { max-width: 700px; }
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
    .col-md-3, .col-md-6, .col-md-9 { flex: 0 0 100%; }
    .steps-timeline { padding-left: 50px; }
    .timeline-number { left: -50px; width: 35px; height: 35px; font-size: 1rem; }
}
</style>

<?php include '../includes/footer.php'; ?>
