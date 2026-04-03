<?php
/**
 * ActeRomânia CMS - Contact Section Manager
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Contact';
$currentPage = 'contact';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: contact.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_contact') {
        try {
            $stmt = $pdo->prepare("UPDATE contact SET 
                title = ?, description = ?, 
                phone = ?, email = ?, whatsapp = ?, 
                address = ?,
                map_embed = ?,
                form_title = ?,
                updated_at = NOW() 
                WHERE id = 1");
            
            $stmt->execute([
                sanitizeInput($_POST['title']),
                sanitizeInput($_POST['description']),
                sanitizeInput($_POST['phone']),
                sanitizeInput($_POST['email']),
                sanitizeInput($_POST['whatsapp']),
                sanitizeInput($_POST['address']),
                $_POST['map_embed'], // Allow HTML for embed
                sanitizeInput($_POST['form_title'])
            ]);
            sync_contact_from_db($pdo, 1);
            
            $_SESSION['flash_message'] = 'Informațiile de contact au fost actualizate!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: contact.php');
    exit;
}

// Get contact data
$contact = $pdo->query("SELECT * FROM contact WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    <input type="hidden" name="action" value="update_contact">
    
    <div class="content-card">
        <div class="card-header">
            <h2>Setări Secțiune Contact</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Titlu Secțiune</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($contact['title'] ?? 'Contactează-ne'); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="description">Descriere</label>
                    <input type="text" id="description" name="description" class="form-control" 
                           value="<?php echo htmlspecialchars($contact['description'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-card mt-4">
        <div class="card-header">
            <h2>Informații de Contact</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="phone">Telefon</label>
                    <input type="text" id="phone" name="phone" class="form-control" 
                           value="<?php echo htmlspecialchars($contact['phone'] ?? ''); ?>" 
                           placeholder="+40 XXX XXX XXX">
                </div>
                <div class="form-group col-md-4">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($contact['email'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="text" id="whatsapp" name="whatsapp" class="form-control" 
                           value="<?php echo htmlspecialchars($contact['whatsapp'] ?? ''); ?>"
                           placeholder="+40 XXX XXX XXX">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="address">Adresă</label>
                    <textarea id="address" name="address" class="form-control" rows="2"><?php echo htmlspecialchars($contact['address'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-card mt-4">
        <div class="card-header">
            <h2>Hartă Google Maps</h2>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="map_embed">Cod Embed Google Maps</label>
                <textarea id="map_embed" name="map_embed" class="form-control" rows="4" 
                          placeholder='<iframe src="https://www.google.com/maps/embed?..." ...></iframe>'><?php echo htmlspecialchars($contact['map_embed'] ?? ''); ?></textarea>
                <small class="form-text">Copiați codul iframe de la Google Maps. Lăsați gol dacă nu doriți să afișați harta.</small>
            </div>
            
            <?php if (!empty($contact['map_embed'])): ?>
            <div class="map-preview">
                <label>Previzualizare Hartă:</label>
                <div class="map-container">
                    <?php echo $contact['map_embed']; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="content-card mt-4">
        <div class="card-header">
            <h2>Formular de Contact</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="form_title">Titlu Formular</label>
                    <input type="text" id="form_title" name="form_title" class="form-control" 
                           value="<?php echo htmlspecialchars($contact['form_title'] ?? 'Trimite-ne un mesaj'); ?>">
                </div>
            </div>
            
            <div class="info-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <strong>Procesare Mesaje</strong>
                    <p>Mesajele trimise prin formularul de contact vor fi trimise la adresa de email configurată mai sus. Asigurați-vă că aveți un server de email configurat sau folosiți un serviciu extern pentru trimiterea emailurilor.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-actions-fixed">
        <button type="submit" class="btn btn-primary btn-lg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Salvează Toate Modificările
        </button>
    </div>
</form>

<style>
.form-row { display: flex; gap: 1.5rem; margin-bottom: 1rem; }
.form-row .form-group { flex: 1; margin-bottom: 0; }
.col-md-4 { flex: 0 0 calc(33.333% - 1rem); }
.col-md-6 { flex: 0 0 calc(50% - 0.75rem); }
.mt-4 { margin-top: 2rem; }

.map-preview {
    margin-top: 1.5rem;
}
.map-preview label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #64748b;
}
.map-container {
    border-radius: 12px;
    overflow: hidden;
    height: 300px;
}
.map-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.info-box {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    margin-top: 1rem;
}
.info-box svg {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    color: #0284c7;
}
.info-box strong {
    display: block;
    color: #0369a1;
    margin-bottom: 0.25rem;
}
.info-box p {
    margin: 0;
    color: #0369a1;
    font-size: 0.875rem;
}

.form-actions-fixed {
    position: sticky;
    bottom: 0;
    background: white;
    padding: 1.5rem;
    margin: 2rem -2rem -2rem -2rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .form-row { flex-direction: column; gap: 0; }
    .col-md-4, .col-md-6 { flex: 0 0 100%; }
    .form-actions-fixed { margin: 2rem -1rem -1rem -1rem; padding: 1rem; }
}
</style>

<?php include '../includes/footer.php'; ?>
