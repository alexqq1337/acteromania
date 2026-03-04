<?php
/**
 * ActeRomânia CMS - Reviews Section Manager
 * Gestionează recenziile trimise de utilizatori
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Recenzii';
$currentPage = 'reviews';

// Optional filter: all | pending | approved
$filter = $_GET['filter'] ?? 'all';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: reviews.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'approve_review') {
        try {
            $stmt = $pdo->prepare("UPDATE reviews SET approved = 1, approved_at = NOW() WHERE id = ?");
            $stmt->execute([(int)$_POST['review_id']]);
            $_SESSION['flash_message'] = 'Recenzie aprobată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'reject_review') {
        try {
            $stmt = $pdo->prepare("UPDATE reviews SET approved = 0, approved_at = NULL WHERE id = ?");
            $stmt->execute([(int)$_POST['review_id']]);
            $_SESSION['flash_message'] = 'Recenzie respinsă!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'update_review') {
        try {
            $stmt = $pdo->prepare("UPDATE reviews SET 
                name = ?, title = ?, message = ?, rating = ? 
                WHERE id = ?");
            $stmt->execute([
                sanitizeInput($_POST['name']),
                sanitizeInput($_POST['title']),
                sanitizeInput($_POST['message']),
                (int)$_POST['rating'],
                (int)$_POST['review_id']
            ]);
            $_SESSION['flash_message'] = 'Recenzie actualizată!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    if ($action === 'delete_review') {
        try {
            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([(int)$_POST['review_id']]);
            $_SESSION['flash_message'] = 'Recenzie ștearsă!';
            $_SESSION['flash_type'] = 'success';
        } catch (PDOException $e) {
            $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: reviews.php');
    exit;
}

// Counts for tabs
$pendingCount = $pdo->query("SELECT COUNT(*) FROM reviews WHERE approved = 0")->fetchColumn();
$approvedCount = $pdo->query("SELECT COUNT(*) FROM reviews WHERE approved = 1")->fetchColumn();

// Get reviews according to filter
if ($filter === 'pending') {
    $allReviews = $pdo->query("SELECT * FROM reviews WHERE approved = 0 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} elseif ($filter === 'approved') {
    $allReviews = $pdo->query("SELECT * FROM reviews WHERE approved = 1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} else {
    $allReviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
}

include '../includes/header.php';
?>

<!-- Reviews Section (edit & delete only) -->
<div class="content-card">
    <div class="card-header">
        <h2>Toate recenziile <span class="badge badge-success"><?php echo count($allReviews); ?></span></h2>
    </div>
    <div class="card-body">
            <div class="admin-tabs" style="margin-bottom:1rem; display:flex; gap:0.5rem; align-items:center;">
                <a href="reviews.php?filter=all" class="btn btn-sm <?php echo $filter==='all' ? 'btn-primary' : 'btn-outline'; ?>">Toate <span class="badge"><?php echo $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn(); ?></span></a>
                <a href="reviews.php?filter=pending" class="btn btn-sm <?php echo $filter==='pending' ? 'btn-primary' : 'btn-outline'; ?>">În așteptare <span class="badge"><?php echo $pendingCount; ?></span></a>
                <a href="reviews.php?filter=approved" class="btn btn-sm <?php echo $filter==='approved' ? 'btn-primary' : 'btn-outline'; ?>">Aprobate <span class="badge"><?php echo $approvedCount; ?></span></a>
            </div>
        <?php if (empty($allReviews)): ?>
            <div class="empty-state"><p>Nu există încă recenzii.</p></div>
        <?php else: ?>
            <div class="reviews-grid">
                <?php foreach ($allReviews as $review): ?>
                <div class="review-card <?php echo $review['approved'] ? 'approved' : 'pending'; ?>">
                    <div class="review-header">
                        <div class="review-avatar"><?php echo strtoupper(substr($review['name'], 0, 1)); ?></div>
                        <div>
                            <strong><?php echo htmlspecialchars($review['name']); ?></strong>
                            <span><?php echo htmlspecialchars($review['email']); ?></span>
                            <span class="review-date"><?php echo date('d.m.Y H:i', strtotime($review['created_at'])); ?></span>
                        </div>
                    </div>
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <?php if (!empty($review['title'])): ?>
                    <h4 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h4>
                    <?php endif; ?>
                    <p class="review-message">"<?php echo htmlspecialchars($review['message']); ?>"</p>
                    <?php if (!empty($review['approved_at'])): ?>
                        <div class="review-meta">
                            <small>Aprobat automat la: <?php echo date('d.m.Y H:i', strtotime($review['approved_at'])); ?></small>
                        </div>
                    <?php elseif (!$review['approved']): ?>
                        <div class="review-meta">
                            <small>Status: neaprobat (sistem vechi)</small>
                        </div>
                    <?php endif; ?>
                    <div class="review-actions">
                        <button type="button" class="btn btn-sm btn-outline" 
                                onclick="editReview(<?php echo htmlspecialchars(json_encode($review)); ?>)">Editează</button>

                        <?php if (empty($review['approved']) || $review['approved'] == 0): ?>
                        <form method="POST" style="display:inline" onsubmit="return confirm('Aprobați această recenzie?')">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="approve_review">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-success">Aprobă</button>
                        </form>
                        <?php else: ?>
                        <form method="POST" style="display:inline" onsubmit="return confirm('Doriți să respingeți (dezaprobe) această recenzie?')">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="reject_review">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-warning">Respinge</button>
                        </form>
                        <?php endif; ?>

                        <form method="POST" style="display:inline" onsubmit="return confirmDelete()">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="delete_review">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline btn-danger">Șterge</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Review Modal -->
<div class="modal" id="reviewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editează Recenzie</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" id="reviewForm">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_review">
                <input type="hidden" name="review_id" id="reviewId">
                
                <div class="form-group">
                    <label for="name">Nume</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="title">Titlu Recenzie</label>
                    <input type="text" id="title" name="title" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="message">Mesaj</label>
                    <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select id="rating" name="rating" class="form-control">
                        <option value="5">5 Stele</option>
                        <option value="4">4 Stele</option>
                        <option value="3">3 Stele</option>
                        <option value="2">2 Stele</option>
                        <option value="1">1 Stea</option>
                    </select>
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
function editReview(review) {
    document.getElementById('reviewId').value = review.id;
    document.getElementById('name').value = review.name;
    document.getElementById('title').value = review.title || '';
    document.getElementById('message').value = review.message;
    document.getElementById('rating').value = review.rating;
    document.getElementById('reviewModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

document.getElementById('reviewModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function confirmDelete() {
    return confirm('Sigur doriți să ștergeți această recenzie?');
}
</script>

<style>
.mt-4 { margin-top: 2rem; }

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-left: 0.5rem;
}
.badge-warning { background: #fef3c7; color: #d97706; }
.badge-success { background: #dcfce7; color: #16a34a; }

.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.review-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
}
.review-card.pending { border-color: #fbbf24; background: #fffbeb; }
.review-card.approved { border-color: #22c55e; }

.review-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.review-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1a365d, #2d4a7c);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.review-header strong { display: block; color: #1a365d; }
.review-header span { display: block; font-size: 0.875rem; color: #64748b; }
.review-date { font-size: 0.75rem !important; color: #94a3b8 !important; }

.review-rating { margin-bottom: 0.75rem; }
.star { color: #e2e8f0; font-size: 1.1rem; }
.star.filled { color: #c9a227; }

.review-title {
    color: #1a365d;
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
}

.review-message {
    color: #475569;
    font-style: italic;
    margin: 0 0 1rem 0;
    line-height: 1.6;
}

.review-meta {
    margin-bottom: 1rem;
    color: #94a3b8;
}

.review-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-success { background: #22c55e; color: white; border-color: #22c55e; }
.btn-success:hover { background: #16a34a; border-color: #16a34a; }
.btn-warning { color: #d97706; border-color: #d97706; }
.btn-warning:hover { background: #d97706; color: white; }

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
    .reviews-grid { grid-template-columns: 1fr; }
}
</style>

<?php include '../includes/footer.php'; ?>
