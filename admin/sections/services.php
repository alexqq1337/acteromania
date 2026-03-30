<?php
/**
 * ActeRomânia CMS - Services Section Manager
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Servicii';
$currentPage = 'services';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: services.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_section') {
        try {
            $stmt = $pdo->prepare("UPDATE services_section SET title = ?, description = ?, updated_at = NOW() WHERE id = 1");
            $stmt->execute([sanitizeInput($_POST['title']), sanitizeInput($_POST['description'])]);
            $_SESSION['flash_message'] = 'Secțiunea actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'add_service') {
        try {
            $imagePath = '';
            if (!empty($_FILES['image']['name'])) {
                $imagePath = uploadImage($_FILES['image'], 'services');
            }
            $offersTransport = !empty($_POST['offers_transport']) ? 1 : 0;
            
            $stmt = $pdo->prepare("INSERT INTO services (title, short_description, full_description, icon_svg, image_url, features, sort_order, offers_transport) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM services")->fetchColumn();
            $stmt->execute([
                sanitizeInput($_POST['title']),
                $_POST['short_description'],
                $_POST['full_description'],
                sanitizeInput($_POST['icon']),
                $imagePath,
                $_POST['features'],
                $order,
                $offersTransport
            ]);
            $_SESSION['flash_message'] = 'Serviciu adăugat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'update_service') {
        try {
            $imagePath = $_POST['current_image'] ?? '';
            if (!empty($_FILES['image']['name'])) {
                $uploaded = uploadImage($_FILES['image'], 'services');
                if ($uploaded) {
                    if ($imagePath) deleteImage($imagePath);
                    $imagePath = $uploaded;
                }
            }
            
            $offersTransport = !empty($_POST['offers_transport']) ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE services SET 
                title = ?, short_description = ?, full_description = ?, icon_svg = ?, image_url = ?, 
                features = ?, offers_transport = ?, updated_at = NOW() 
                WHERE id = ?");
            $stmt->execute([
                sanitizeInput($_POST['title']),
                $_POST['short_description'],
                $_POST['full_description'],
                sanitizeInput($_POST['icon']),
                $imagePath,
                $_POST['features'],
                $offersTransport,
                (int)$_POST['service_id']
            ]);
            $_SESSION['flash_message'] = 'Serviciu actualizat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_service') {
        try {
            // Get image path first
            $stmt = $pdo->prepare("SELECT image_url FROM services WHERE id = ?");
            $stmt->execute([(int)$_POST['service_id']]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($service && $service['image_url']) {
                deleteImage($service['image_url']);
            }
            
            $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([(int)$_POST['service_id']]);
            $_SESSION['flash_message'] = 'Serviciu șters!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'toggle_active') {
        try {
            $stmt = $pdo->prepare("UPDATE services SET enabled = NOT enabled WHERE id = ?");
            $stmt->execute([(int)$_POST['service_id']]);
            $_SESSION['flash_message'] = 'Status actualizat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: services.php');
    exit;
}

// Get data
$section = $pdo->query("SELECT * FROM services_section WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="content-card">
    <div class="card-header">
        <h2>Setări Secțiune Servicii</h2>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="update_section">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Titlu Secțiune</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($section['title'] ?? 'Serviciile Noastre'); ?>" required>
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
        <h2>Lista Servicii</h2>
        <button type="button" class="btn btn-sm btn-primary" onclick="openServiceModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Adaugă Serviciu
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($services)): ?>
            <div class="empty-state">
                <p>Nu există servicii. Adăugați primul serviciu.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="80">Imagine</th>
                            <th>Titlu</th>
                            <th width="100">Status</th>
                            <th width="150">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $index => $service): ?>
                        <tr class="<?php echo !$service['enabled'] ? 'inactive' : ''; ?>">
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php if ($service['image_url']): ?>
                                    <img src="<?php echo SITE_URL . '/' . $service['image_url']; ?>" 
                                         alt="<?php echo htmlspecialchars($service['title']); ?>" 
                                         class="table-thumb">
                                <?php else: ?>
                                    <div class="table-thumb-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($service['title']); ?></strong>
                            </td>
                            <td>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="toggle_active">
                                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                    <button type="submit" class="status-toggle <?php echo $service['enabled'] ? 'active' : ''; ?>">
                                        <?php echo $service['enabled'] ? 'Activ' : 'Inactiv'; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-outline" 
                                            onclick="editService(<?php echo htmlspecialchars(json_encode(array_merge($service, ['icon_svg' => html_entity_decode($service['icon_svg'])]))); ?>)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>
                                    <form method="POST" style="display:inline" onsubmit="return confirmDelete('Sigur doriți să ștergeți acest serviciu?')">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="action" value="delete_service">
                                        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline btn-danger">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Service Modal -->
<div class="modal" id="serviceModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3 id="serviceModalTitle">Adaugă Serviciu</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="serviceForm">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" id="serviceAction" value="add_service">
                <input type="hidden" name="service_id" id="serviceId">
                <input type="hidden" name="current_image" id="currentImage">
                
                <div class="form-group">
                        <label for="service_title">Titlu Serviciu</label>
                        <input type="text" id="service_title" name="title" class="form-control" required>
                    </div>
                
                <div class="form-group">
                    <label for="service_short_description">Descriere scurtă (pentru cartonas)</label>
                    <textarea id="service_short_description" name="short_description" class="form-control" rows="2" placeholder="Descriere scurtă vizibilă pe cartonas"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="service_full_description">Descriere completă (pentru detalii)</label>
                    <textarea id="service_full_description" name="full_description" class="form-control" rows="4" placeholder="Descriere detaliată vizibilă când se extinde cartonasul"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="service_icon">Icon SVG</label>
                        <textarea id="service_icon" name="icon" class="form-control" rows="3" placeholder="Codul SVG pentru icon"></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Imagine Serviciu</label>
                        <div class="image-upload-small">
                            <div class="image-preview" id="serviceImagePreview">
                                <div class="upload-placeholder">
                                    <span>Click pentru imagine</span>
                                </div>
                            </div>
                            <input type="file" name="image" id="serviceImage" accept="image/*" 
                                   onchange="previewImage(this, 'serviceImagePreview')">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="service_features">Caracteristici (una pe linie)</label>
                    <textarea id="service_features" name="features" class="form-control" rows="4" 
                              placeholder="Caracteristica 1&#10;Caracteristica 2&#10;Caracteristica 3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="toggle-switch">
                        <input type="checkbox" name="offers_transport" id="service_offers_transport" value="1">
                        <span class="toggle-slider"></span>
                        <span>Se oferă transport (afișat în detalii pe site)</span>
                    </label>
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
function openServiceModal() {
    document.getElementById('serviceModalTitle').textContent = 'Adaugă Serviciu';
    document.getElementById('serviceAction').value = 'add_service';
    document.getElementById('serviceForm').reset();
    document.getElementById('service_offers_transport').checked = false;
    document.getElementById('serviceImagePreview').innerHTML = '<div class="upload-placeholder"><span>Click pentru imagine</span></div>';
    document.getElementById('serviceImagePreview').classList.remove('has-image');
    document.getElementById('serviceModal').style.display = 'flex';
}

function editService(service) {
    document.getElementById('serviceModalTitle').textContent = 'Editează Serviciu';
    document.getElementById('serviceAction').value = 'update_service';
    document.getElementById('serviceId').value = service.id;
    document.getElementById('currentImage').value = service.image_url || '';
    document.getElementById('service_title').value = service.title;
    document.getElementById('service_short_description').value = service.short_description || '';
    document.getElementById('service_full_description').value = service.full_description || '';
    document.getElementById('service_icon').value = service.icon_svg || '';
    document.getElementById('service_features').value = service.features || '';
    document.getElementById('service_offers_transport').checked = !!Number(service.offers_transport);
    
    if (service.image_url) {
        document.getElementById('serviceImagePreview').innerHTML = 
            '<img src="<?php echo SITE_URL; ?>/' + service.image_url + '" alt="Preview">';
        document.getElementById('serviceImagePreview').classList.add('has-image');
    } else {
        document.getElementById('serviceImagePreview').innerHTML = 
            '<div class="upload-placeholder"><span>Click pentru imagine</span></div>';
        document.getElementById('serviceImagePreview').classList.remove('has-image');
    }
    
    document.getElementById('serviceModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('serviceModal').style.display = 'none';
}

document.getElementById('serviceModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

<style>
.form-row { display: flex; gap: 1.5rem; margin-bottom: 1rem; }
.form-row .form-group { flex: 1; margin-bottom: 0; }
.col-md-4 { flex: 0 0 33.333%; }
.col-md-6 { flex: 0 0 calc(50% - 0.75rem); }
.col-md-8 { flex: 0 0 66.666%; }
.mt-4 { margin-top: 2rem; }

.table-responsive { overflow-x: auto; }
.admin-table {
    width: 100%;
    border-collapse: collapse;
}
.admin-table th, .admin-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}
.admin-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #64748b;
    font-size: 0.75rem;
    text-transform: uppercase;
}
.admin-table tr.inactive { opacity: 0.5; }
.admin-table tr:hover { background: #f8fafc; }

.table-thumb {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}
.table-thumb-placeholder {
    width: 60px;
    height: 40px;
    background: #f1f5f9;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.table-thumb-placeholder svg {
    width: 20px;
    height: 20px;
    color: #94a3b8;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 4px;
}
.badge-gold { background: #fef3c7; color: #92400e; }

.status-toggle {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    border: none;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    background: #fee2e2;
    color: #dc2626;
    transition: all 0.2s;
}
.status-toggle.active {
    background: #dcfce7;
    color: #16a34a;
}

.action-buttons {
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
    position: sticky;
    top: 0;
    background: white;
    z-index: 1;
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
    position: sticky;
    bottom: 0;
    background: white;
}

.image-upload-small .image-preview {
    width: 100%;
    height: 120px;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #64748b;
}

.toggle-switch {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}
.toggle-switch input { display: none; }
.toggle-slider {
    width: 44px;
    height: 24px;
    background: #e2e8f0;
    border-radius: 12px;
    position: relative;
    transition: all 0.3s;
}
.toggle-slider::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    top: 2px;
    left: 2px;
    transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.toggle-switch input:checked + .toggle-slider {
    background: #1a365d;
}
.toggle-switch input:checked + .toggle-slider::after {
    left: 22px;
}

@media (max-width: 768px) {
    .form-row { flex-direction: column; gap: 0; }
    .col-md-4, .col-md-6, .col-md-8 { flex: 0 0 100%; }
}
</style>

<?php include '../includes/footer.php'; ?>
