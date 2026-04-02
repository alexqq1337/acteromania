<?php
/**
 * ActeRomânia CMS - Media Library
 */
require_once '../config.php';
requireLogin();

$pageTitle = 'Media Library';
$currentPage = 'media';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: media.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'upload') {
        if (!empty($_FILES['files']['name'][0])) {
            $uploaded = 0;
            $failed = 0;
            
            foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                $file = [
                    'name' => $_FILES['files']['name'][$key],
                    'type' => $_FILES['files']['type'][$key],
                    'tmp_name' => $tmpName,
                    'error' => $_FILES['files']['error'][$key],
                    'size' => $_FILES['files']['size'][$key]
                ];
                
                $path = uploadImage($file, 'library');
                if ($path) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO media (filename, filepath, filetype, filesize) VALUES (?, ?, ?, ?)");
                        $stmt->execute([
                            $file['name'],
                            $path,
                            $file['type'],
                            $file['size']
                        ]);
                        $uploaded++;
                    } catch (PDOException $e) {
                        $failed++;
                    }
                } else {
                    $failed++;
                }
            }
            
            $_SESSION['flash_message'] = "$uploaded fișiere încărcate" . ($failed > 0 ? ", $failed eșuate" : "");
            $_SESSION['flash_type'] = $failed > 0 ? 'warning' : 'success';
        }
    }
    
    if ($action === 'delete') {
        try {
            $stmt = $pdo->prepare("SELECT filepath FROM media WHERE id = ?");
            $stmt->execute([(int)$_POST['media_id']]);
            $media = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($media) {
                deleteImage($media['filepath']);
                $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
                $stmt->execute([(int)$_POST['media_id']]);
                $_SESSION['flash_message'] = 'Fișier șters!';
                $_SESSION['flash_type'] = 'success';
            }
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: media.php');
    exit;
}

// Get media files
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 24;
$offset = ($page - 1) * $perPage;

$totalMedia = $pdo->query("SELECT COUNT(*) FROM media")->fetchColumn();
$totalPages = ceil($totalMedia / $perPage);

$media = $pdo->query("SELECT * FROM media ORDER BY created_at DESC LIMIT $perPage OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="content-card">
    <div class="card-header">
        <h2>Biblioteca Media</h2>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('uploadModal').style.display='flex'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            Încarcă Fișiere
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($media)): ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                <p>Nu există fișiere în bibliotecă.</p>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('uploadModal').style.display='flex'">
                    Încarcă Primul Fișier
                </button>
            </div>
        <?php else: ?>
            <div class="media-grid">
                <?php foreach ($media as $item): ?>
                <div class="media-item" data-id="<?php echo $item['id']; ?>">
                    <div class="media-preview">
                        <?php if (strpos($item['filetype'], 'image') !== false): ?>
                            <img src="<?php echo SITE_URL . '/' . $item['filepath']; ?>" alt="<?php echo htmlspecialchars($item['filename']); ?>">
                        <?php else: ?>
                            <div class="file-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="media-info">
                        <span class="media-name"><?php echo htmlspecialchars($item['filename']); ?></span>
                        <span class="media-size"><?php echo formatFileSize($item['filesize']); ?></span>
                    </div>
                    <div class="media-actions">
                        <button type="button" class="btn btn-sm btn-outline" 
                                onclick="copyToClipboard('<?php echo SITE_URL . '/' . $item['filepath']; ?>')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                        </button>
                        <form method="POST" style="display:inline" onsubmit="return confirmDelete('Sigur doriți să ștergeți acest fișier?')">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="media_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline btn-danger">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="btn btn-outline">← Anterior</a>
                <?php endif; ?>
                
                <span class="page-info">Pagina <?php echo $page; ?> din <?php echo $totalPages; ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="btn btn-outline">Următor →</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal" id="uploadModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Încarcă Fișiere</h3>
            <button type="button" class="modal-close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="upload">
                
                <div class="upload-dropzone" id="dropzone">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    <p>Trageți fișierele aici sau click pentru a selecta</p>
                    <small>JPG, PNG, GIF, WEBP, PDF - Max 5MB per fișier</small>
                    <input type="file" name="files[]" id="fileInput" multiple accept="image/*,.pdf">
                </div>
                
                <div class="selected-files" id="selectedFiles"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="this.closest('.modal').style.display='none'">Anulează</button>
                <button type="submit" class="btn btn-primary">Încarcă</button>
            </div>
        </form>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('Link copiat în clipboard!', 'success');
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Drag and drop
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const selectedFiles = document.getElementById('selectedFiles');

dropzone.addEventListener('click', () => fileInput.click());

dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('dragover');
});

dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('dragover');
});

dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    updateSelectedFiles();
});

fileInput.addEventListener('change', updateSelectedFiles);

function updateSelectedFiles() {
    const files = fileInput.files;
    if (files.length === 0) {
        selectedFiles.innerHTML = '';
        return;
    }
    
    let html = '<div class="files-list">';
    for (const file of files) {
        html += `<div class="file-item">
            <span>${file.name}</span>
            <span class="file-size">${formatFileSize(file.size)}</span>
        </div>`;
    }
    html += '</div>';
    selectedFiles.innerHTML = html;
}

document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>

<style>
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1.5rem;
}

.media-item {
    background: #f8fafc;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}
.media-item:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.media-preview {
    aspect-ratio: 1;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.media-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.file-icon {
    color: #64748b;
}
.file-icon svg {
    width: 48px;
    height: 48px;
}

.media-info {
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.media-name {
    font-size: 0.875rem;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.media-size {
    font-size: 0.75rem;
    color: #64748b;
}

.media-actions {
    padding: 0 0.75rem 0.75rem;
    display: flex;
    gap: 0.5rem;
}

.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}
.page-info {
    color: #64748b;
}

.upload-dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}
.upload-dropzone:hover,
.upload-dropzone.dragover {
    border-color: #1a365d;
    background: #f0f9ff;
}
.upload-dropzone svg {
    width: 48px;
    height: 48px;
    color: #64748b;
    margin-bottom: 1rem;
}
.upload-dropzone p {
    margin: 0 0 0.5rem;
    color: #334155;
}
.upload-dropzone small {
    color: #64748b;
}
.upload-dropzone input {
    display: none;
}

.files-list {
    margin-top: 1rem;
}
.file-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}
.file-size { color: #64748b; }

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}
.empty-state svg {
    width: 80px;
    height: 80px;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}
.empty-state p {
    margin-bottom: 1.5rem;
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
</style>

<?php 
function formatFileSize($bytes) {
    if ($bytes === 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}

include 'includes/footer.php'; 
?>
