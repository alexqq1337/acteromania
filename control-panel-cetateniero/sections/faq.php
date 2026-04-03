<?php
/**
 * ActeRomânia CMS - FAQ Section Manager
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Întrebări Frecvente';
$currentPage = 'faq';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: faq.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_section') {
        try {
            $stmt = $pdo->prepare("UPDATE faq_section SET title = ?, updated_at = NOW() WHERE id = 1");
            $stmt->execute([sanitizeInput($_POST['title'])]);
            sync_faq_section_from_db($pdo, 1);
            $_SESSION['flash_message'] = 'Secțiunea actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'add_faq') {
        try {
            $stmt = $pdo->prepare("INSERT INTO faq (question, answer, sort_order) VALUES (?, ?, ?)");
            $order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM faq")->fetchColumn();
            $stmt->execute([
                sanitizeInput($_POST['question']),
                $_POST['answer'],
                $order
            ]);
            $fid = (int)$pdo->lastInsertId();
            if ($fid > 0) {
                sync_faq_from_base($pdo, $fid);
            }
            $_SESSION['flash_message'] = 'Întrebare adăugată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'update_faq') {
        try {
            $stmt = $pdo->prepare("UPDATE faq SET question = ?, answer = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([
                sanitizeInput($_POST['question']),
                $_POST['answer'],
                (int)$_POST['faq_id']
            ]);
            sync_faq_from_base($pdo, (int)$_POST['faq_id']);
            $_SESSION['flash_message'] = 'Întrebare actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_faq') {
        try {
            $stmt = $pdo->prepare("DELETE FROM faq WHERE id = ?");
            $stmt->execute([(int)$_POST['faq_id']]);
            $_SESSION['flash_message'] = 'Întrebare ștearsă!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'toggle_active') {
        try {
            $stmt = $pdo->prepare("UPDATE faq SET enabled = NOT enabled WHERE id = ?");
            $stmt->execute([(int)$_POST['faq_id']]);
            $_SESSION['flash_message'] = 'Status actualizat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: faq.php');
    exit;
}

// Get data
$section = $pdo->query("SELECT * FROM faq_section WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$faqs = $pdo->query("SELECT * FROM faq ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

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
                           value="<?php echo htmlspecialchars($section['title'] ?? 'Întrebări Frecvente'); ?>" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvează</button>
            </div>
        </form>
    </div>
</div>

<div class="content-card mt-4">
    <div class="card-header">
        <h2>Întrebări și Răspunsuri</h2>
        <button type="button" class="btn btn-sm btn-primary" onclick="openModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Adaugă Întrebare
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($faqs)): ?>
            <div class="empty-state"><p>Nu există întrebări frecvente.</p></div>
        <?php else: ?>
            <div class="faq-list">
                <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item <?php echo !$faq['enabled'] ? 'inactive' : ''; ?>">
                    <div class="faq-header">
                        <span class="faq-number"><?php echo $index + 1; ?></span>
                        <div class="faq-question">
                            <strong><?php echo htmlspecialchars($faq['question']); ?></strong>
                            <p><?php echo htmlspecialchars(substr(strip_tags($faq['answer']), 0, 100)); ?>...</p>
                        </div>
                        <div class="faq-actions">
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="toggle_active">
                                <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>">
                                <button type="submit" class="status-toggle <?php echo $faq['enabled'] ? 'active' : ''; ?>">
                                    <?php echo $faq['enabled'] ? 'Activ' : 'Inactiv'; ?>
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-outline" 
                                    onclick="editItem(<?php echo htmlspecialchars(json_encode($faq)); ?>)">Editează</button>
                            <form method="POST" style="display:inline" onsubmit="return confirmDelete()">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="delete_faq">
                                <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline btn-danger">Șterge</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- FAQ Modal -->
<div class="modal" id="itemModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3 id="modalTitle">Adaugă Întrebare</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" id="itemForm">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" id="formAction" value="add_faq">
                <input type="hidden" name="faq_id" id="itemId">
                
                <div class="form-group">
                    <label for="question">Întrebare</label>
                    <input type="text" id="question" name="question" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="answer">Răspuns</label>
                    <textarea id="answer" name="answer" class="form-control" rows="6" required></textarea>
                    <small class="form-text">Puteți folosi HTML de bază pentru formatare</small>
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
function openModal() {
    document.getElementById('modalTitle').textContent = 'Adaugă Întrebare';
    document.getElementById('formAction').value = 'add_faq';
    document.getElementById('itemForm').reset();
    document.getElementById('itemModal').style.display = 'flex';
}

function editItem(item) {
    document.getElementById('modalTitle').textContent = 'Editează Întrebare';
    document.getElementById('formAction').value = 'update_faq';
    document.getElementById('itemId').value = item.id;
    document.getElementById('question').value = item.question;
    document.getElementById('answer').value = item.answer;
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

.faq-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.faq-item {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
}
.faq-item.inactive { opacity: 0.5; }

.faq-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.faq-number {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #1a365d, #2d4a7c);
    color: #c9a227;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.faq-question {
    flex: 1;
}
.faq-question strong {
    display: block;
    color: #1a365d;
    margin-bottom: 0.25rem;
}
.faq-question p {
    margin: 0;
    color: #64748b;
    font-size: 0.875rem;
}

.faq-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.status-toggle {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    border: none;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    background: #fee2e2;
    color: #dc2626;
}
.status-toggle.active { background: #dcfce7; color: #16a34a; }

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
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.modal-lg { max-width: 650px; }
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

.empty-state { text-align: center; padding: 3rem; color: #64748b; }

@media (max-width: 768px) {
    .form-row { flex-direction: column; gap: 0; }
    .col-md-6 { flex: 0 0 100%; }
    .faq-header { flex-wrap: wrap; }
    .faq-actions { width: 100%; margin-top: 1rem; }
}
</style>

<?php include '../includes/footer.php'; ?>
