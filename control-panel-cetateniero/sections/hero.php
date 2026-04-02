<?php
/**
 * ActeRomânia CMS - Hero Section Manager
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Secțiunea Acasă';
$currentPage = 'hero';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: hero.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_hero') {
        try {
            // Handle image upload
            $imagePath = $_POST['current_image'] ?? '';
            if (!empty($_FILES['image']['name'])) {
                $uploaded = uploadImage($_FILES['image'], 'hero');
                if ($uploaded) {
                    // Delete old image if exists
                    if ($imagePath) {
                        deleteImage($imagePath);
                    }
                    $imagePath = $uploaded;
                }
            }
            
            // Handle video upload
            $videoPath = $_POST['current_video'] ?? '';
            $videoType = $_POST['video_type'] ?? 'upload';
            
            if ($videoType === 'upload' && !empty($_FILES['video']['name'])) {
                $uploaded = uploadVideo($_FILES['video'], 'hero');
                if ($uploaded) {
                    // Delete old video if exists
                    if ($videoPath) {
                        deleteImage($videoPath);
                    }
                    $videoPath = $uploaded;
                }
            } elseif ($videoType === 'youtube' || $videoType === 'vimeo') {
                $videoPath = sanitizeInput($_POST['video_url'] ?? '');
            }
            
            $mediaType = $_POST['media_type'] ?? 'image';
            
            // If no hero row exists, insert one. Otherwise update existing row (id=1)
            $exists = (int)$pdo->query("SELECT COUNT(*) FROM hero WHERE id = 1")->fetchColumn();
            if ($exists === 0) {
                $stmt = $pdo->prepare("INSERT INTO hero (id, title, subtitle, cta_text, cta_link, image_url, video_url, video_type, media_type, trust_bar_enabled, created_at, updated_at) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())");
                $stmt->execute([
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['subtitle']),
                    sanitizeInput($_POST['cta_text']),
                    sanitizeInput($_POST['cta_link']),
                    $imagePath,
                    $videoPath,
                    $videoType,
                    $mediaType
                ]);
            } else {
                $stmt = $pdo->prepare("UPDATE hero SET 
                    title = ?, 
                    subtitle = ?, 
                    cta_text = ?, 
                    cta_link = ?,
                    image_url = ?,
                    video_url = ?,
                    video_type = ?,
                    media_type = ?,
                    updated_at = NOW()
                    WHERE id = 1");

                $stmt->execute([
                    sanitizeInput($_POST['title']),
                    sanitizeInput($_POST['subtitle']),
                    sanitizeInput($_POST['cta_text']),
                    sanitizeInput($_POST['cta_link']),
                    $imagePath,
                    $videoPath,
                    $videoType,
                    $mediaType
                ]);
            }
            
            $_SESSION['flash_message'] = 'Secțiunea Acasă actualizată cu succes!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'add_trust_item') {
        try {
            $stmt = $pdo->prepare("INSERT INTO hero_trust_items (icon_svg, text, sort_order) VALUES (?, ?, ?)");
            $order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM hero_trust_items")->fetchColumn();
            $stmt->execute([
                sanitizeInput($_POST['icon']),
                sanitizeInput($_POST['text']),
                $order
            ]);
            $_SESSION['flash_message'] = 'Element de încredere adăugat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'update_trust_item') {
        try {
            $stmt = $pdo->prepare("UPDATE hero_trust_items SET icon_svg = ?, text = ? WHERE id = ?");
            $stmt->execute([
                sanitizeInput($_POST['icon']),
                sanitizeInput($_POST['text']),
                (int)$_POST['item_id']
            ]);
            $_SESSION['flash_message'] = 'Element actualizat!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_trust_item') {
        try {
            $stmt = $pdo->prepare("DELETE FROM hero_trust_items WHERE id = ?");
            $stmt->execute([(int)$_POST['item_id']]);
            $_SESSION['flash_message'] = 'Element șters!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: hero.php');
    exit;
}

// Get hero data
$hero = $pdo->query("SELECT * FROM hero WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$trustItems = $pdo->query("SELECT * FROM hero_trust_items ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="content-card">
    <div class="card-header">
        <h2>Editare Secțiunea Acasă</h2>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="update_hero">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($hero['image_url'] ?? ''); ?>">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Titlu Principal</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($hero['title'] ?? ''); ?>" required>
                    <small class="form-text">Titlul H1 principal al secțiunii hero</small>
                </div>
                <div class="form-group col-md-6">
                    <label for="subtitle">Subtitlu</label>
                    <textarea id="subtitle" name="subtitle" class="form-control" rows="3"><?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cta_text">Text Buton CTA</label>
                    <input type="text" id="cta_text" name="cta_text" class="form-control" 
                           value="<?php echo htmlspecialchars($hero['cta_text'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="cta_link">Link Buton CTA</label>
                    <input type="text" id="cta_link" name="cta_link" class="form-control" 
                           value="<?php echo htmlspecialchars($hero['cta_link'] ?? ''); ?>" placeholder="#contact">
                </div>
            </div>
            
            <div class="form-group">
                <label>Tip Media pentru Fundal</label>
                <div class="media-type-selector">
                    <label class="radio-option">
                        <input type="radio" name="media_type" value="image" 
                               <?php echo ($hero['media_type'] ?? 'image') === 'image' ? 'checked' : ''; ?>
                               onchange="toggleMediaType('image')">
                        <span class="radio-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            Imagine
                        </span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="media_type" value="video" 
                               <?php echo ($hero['media_type'] ?? 'image') === 'video' ? 'checked' : ''; ?>
                               onchange="toggleMediaType('video')">
                        <span class="radio-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                            Video
                        </span>
                    </label>
                </div>
            </div>
            
            <!-- Image Upload Section -->
            <div class="form-group media-section" id="imageSection" style="display: <?php echo ($hero['media_type'] ?? 'image') === 'image' ? 'block' : 'none'; ?>">
                <label>Imagine Hero</label>
                <div class="image-upload-area">
                    <div class="image-preview <?php echo !empty($hero['image_url']) ? 'has-image' : ''; ?>" id="heroImagePreview">
                        <?php if (!empty($hero['image_url'])): ?>
                            <img src="<?php echo SITE_URL . '/' . $hero['image_url']; ?>" alt="Hero Image">
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
                    <input type="file" name="image" id="heroImage" accept="image/*" 
                           onchange="previewImage(this, 'heroImagePreview')">
                    <small class="form-text">Recomandare: 1920x1080px, format JPG/PNG, max 5MB</small>
                </div>
            </div>
            
            <!-- Video Upload Section -->
            <div class="form-group media-section" id="videoSection" style="display: <?php echo ($hero['media_type'] ?? 'image') === 'video' ? 'block' : 'none'; ?>">
                <label>Sursă Video</label>
                <div class="video-type-selector">
                    <label class="radio-option">
                        <input type="radio" name="video_type" value="upload" 
                               <?php echo ($hero['video_type'] ?? 'upload') === 'upload' ? 'checked' : ''; ?>
                               onchange="toggleVideoType('upload')">
                        <span class="radio-label">Încarcă Video</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="video_type" value="youtube" 
                               <?php echo ($hero['video_type'] ?? 'upload') === 'youtube' ? 'checked' : ''; ?>
                               onchange="toggleVideoType('youtube')">
                        <span class="radio-label">YouTube</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="video_type" value="vimeo" 
                               <?php echo ($hero['video_type'] ?? 'upload') === 'vimeo' ? 'checked' : ''; ?>
                               onchange="toggleVideoType('vimeo')">
                        <span class="radio-label">Vimeo</span>
                    </label>
                </div>
                
                <!-- Video Upload -->
                <div class="video-input-section" id="videoUploadSection" style="display: <?php echo ($hero['video_type'] ?? 'upload') === 'upload' ? 'block' : 'none'; ?>">
                    <input type="hidden" name="current_video" value="<?php echo htmlspecialchars($hero['video_url'] ?? ''); ?>">
                    <div class="video-upload-area">
                        <div class="video-preview" id="heroVideoPreview">
                            <?php if (!empty($hero['video_url']) && ($hero['video_type'] ?? 'upload') === 'upload'): ?>
                                <video src="<?php echo SITE_URL . '/' . $hero['video_url']; ?>" controls style="max-width: 100%; max-height: 200px;"></video>
                            <?php else: ?>
                                <div class="upload-placeholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="5 3 19 12 5 21 5 3"/>
                                    </svg>
                                    <span>Click pentru a încărca video</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="video" id="heroVideo" accept="video/mp4,video/webm,video/ogg" 
                               onchange="previewVideo(this, 'heroVideoPreview')">
                        <small class="form-text">Format: MP4, WebM sau OGG. Max 50MB. Recomandare: 1920x1080px</small>
                    </div>
                </div>
                
                <!-- YouTube/Vimeo URL -->
                <div class="video-input-section" id="videoUrlSection" style="display: <?php echo in_array($hero['video_type'] ?? 'upload', ['youtube', 'vimeo']) ? 'block' : 'none'; ?>">
                    <input type="text" name="video_url" id="videoUrl" class="form-control" 
                           value="<?php echo htmlspecialchars((in_array($hero['video_type'] ?? '', ['youtube', 'vimeo']) ? $hero['video_url'] : '') ?? ''); ?>"
                           placeholder="https://www.youtube.com/watch?v=... sau https://vimeo.com/...">
                    <small class="form-text">Introduceți URL-ul complet al video-ului</small>
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
        <h2>Trust Bar - Elemente de încredere</h2>
        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('addTrustModal').style.display='flex'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Adaugă Element
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($trustItems)): ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p>Nu există elemente de încredere. Adăugați primul element.</p>
            </div>
        <?php else: ?>
            <div class="sortable-list">
                <?php foreach ($trustItems as $item): ?>
                <div class="sortable-item" data-id="<?php echo $item['id']; ?>">
                    <div class="drag-handle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="8" y1="18" x2="16" y2="18"/>
                        </svg>
                    </div>
                    <div class="item-content">
                        <div class="item-icon"><?php echo html_entity_decode($item['icon_svg']); ?></div>
                        <span class="item-text"><?php echo htmlspecialchars($item['text']); ?></span>
                    </div>
                    <div class="item-actions">
                        <button type="button" class="btn btn-sm btn-outline" 
                                onclick="editTrustItem(<?php echo $item['id']; ?>, '<?php echo addslashes(html_entity_decode($item['icon_svg'])); ?>', '<?php echo addslashes($item['text']); ?>')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" style="display:inline" onsubmit="return confirmDelete()">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="delete_trust_item">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline btn-danger">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Trust Item Modal -->
<div class="modal" id="addTrustModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Adaugă Element de Încredere</h3>
            <button type="button" class="modal-close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="add_trust_item">
                
                <div class="form-group">
                    <label for="add_icon">Icon (SVG sau Emoji)</label>
                    <input type="text" id="add_icon" name="icon" class="form-control" placeholder="✓ sau cod SVG" required>
                    <small class="form-text">Puteți folosi un emoji sau cod SVG</small>
                </div>
                
                <div class="form-group">
                    <label for="add_text">Text</label>
                    <input type="text" id="add_text" name="text" class="form-control" placeholder="ex: 15+ Ani Experiență" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="this.closest('.modal').style.display='none'">Anulează</button>
                <button type="submit" class="btn btn-primary">Adaugă</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Trust Item Modal -->
<div class="modal" id="editTrustModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editează Element</h3>
            <button type="button" class="modal-close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_trust_item">
                <input type="hidden" name="item_id" id="edit_item_id">
                
                <div class="form-group">
                    <label for="edit_icon">Icon (SVG sau Emoji)</label>
                    <input type="text" id="edit_icon" name="icon" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_text">Text</label>
                    <input type="text" id="edit_text" name="text" class="form-control" required>
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
function editTrustItem(id, icon, text) {
    document.getElementById('edit_item_id').value = id;
    document.getElementById('edit_icon').value = icon;
    document.getElementById('edit_text').value = text;
    document.getElementById('editTrustModal').style.display = 'flex';
}

// Close modal on outside click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Toggle between image and video
function toggleMediaType(type) {
    document.getElementById('imageSection').style.display = type === 'image' ? 'block' : 'none';
    document.getElementById('videoSection').style.display = type === 'video' ? 'block' : 'none';
}

// Toggle video source type
function toggleVideoType(type) {
    document.getElementById('videoUploadSection').style.display = type === 'upload' ? 'block' : 'none';
    document.getElementById('videoUrlSection').style.display = (type === 'youtube' || type === 'vimeo') ? 'block' : 'none';
}

// Preview video upload
function previewVideo(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const url = URL.createObjectURL(file);
        preview.innerHTML = '<video src="' + url + '" controls style="max-width: 100%; max-height: 200px;"></video>';
    }
}
</script>

<style>
.form-row { display: flex; gap: 1.5rem; margin-bottom: 1rem; }
.form-row .form-group { flex: 1; }
.col-md-6 { flex: 0 0 50%; }
.mt-4 { margin-top: 2rem; }
.item-icon { font-size: 1.5rem; margin-right: 1rem; }
.item-text { font-weight: 500; }
.item-content { display: flex; align-items: center; flex: 1; }

/* Media Type Selector */
.media-type-selector,
.video-type-selector {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.radio-option {
    cursor: pointer;
}

.radio-option input[type="radio"] {
    display: none;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s ease;
    background: #f8fafc;
}

.radio-label svg {
    width: 20px;
    height: 20px;
    stroke: #64748b;
}

.radio-option input[type="radio"]:checked + .radio-label {
    border-color: #1a365d;
    background: #eff6ff;
    color: #1a365d;
}

.radio-option input[type="radio"]:checked + .radio-label svg {
    stroke: #1a365d;
}

.video-upload-area {
    margin-top: 1rem;
}

.video-preview {
    width: 100%;
    max-width: 400px;
    min-height: 150px;
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    cursor: pointer;
    overflow: hidden;
}

.video-preview video {
    border-radius: 6px;
}

.video-input-section {
    margin-top: 1rem;
}

.media-section {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    margin-top: 1rem;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
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
.empty-state svg {
    width: 64px;
    height: 64px;
    margin-bottom: 1rem;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .form-row { flex-direction: column; gap: 0; }
    .col-md-6 { flex: 0 0 100%; }
}
</style>

<?php include '../includes/footer.php'; ?>
