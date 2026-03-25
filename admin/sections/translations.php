<?php
/**
 * ActeRomânia CMS - Translations Manager
 * Permite editarea traducerilor pentru conținutul din baza de date
 */
require_once '../../config.php';
requireLogin();

$pageTitle = 'Traduceri';
$currentPage = 'translations';

// Available languages for translation
$languages = ['ru' => 'Русский', 'en' => 'English'];

// Mapping between base content tables and normalized translation tables used by frontend
$translationTableMap = [
    'hero' => ['table' => 'hero_translations', 'fk' => 'hero_id'],
    'about' => ['table' => 'about_translations', 'fk' => 'about_id'],
    'services_section' => ['table' => 'services_section_translations', 'fk' => 'section_id'],
    'services' => ['table' => 'services_translations', 'fk' => 'service_id'],
    'process_section' => ['table' => 'process_section_translations', 'fk' => 'section_id'],
    'process_steps' => ['table' => 'process_steps_translations', 'fk' => 'step_id'],
    'faq_section' => ['table' => 'faq_section_translations', 'fk' => 'section_id'],
    'faq' => ['table' => 'faq_translations', 'fk' => 'faq_id'],
    'contact' => ['table' => 'contact_translations', 'fk' => 'contact_id'],
    'why_us_items' => ['table' => 'why_us_items_translations', 'fk' => 'item_id'],
    'about_stats' => ['table' => 'about_stats_translations', 'fk' => 'stat_id'],
    'hero_trust_items' => ['table' => 'hero_trust_items_translations', 'fk' => 'trust_item_id'],
];

// Cache schema metadata to avoid repeated information_schema checks.
$tableExistsCache = [];
$tableColumnsCache = [];

function translationTableExists(PDO $pdo, array &$cache, string $tableName): bool {
    if (array_key_exists($tableName, $cache)) {
        return $cache[$tableName];
    }

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?"
    );
    $stmt->execute([$tableName]);

    $cache[$tableName] = (int)$stmt->fetchColumn() > 0;
    return $cache[$tableName];
}

function getTableColumns(PDO $pdo, array &$cache, string $tableName): array {
    if (isset($cache[$tableName])) {
        return $cache[$tableName];
    }

    $stmt = $pdo->query("SHOW COLUMNS FROM `$tableName`");
    $columns = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        $columns[$col['Field']] = true;
    }

    $cache[$tableName] = $columns;
    return $columns;
}

// Tables and fields that can be translated
$translatableTables = [
    'hero' => [
        'name' => 'Hero Section',
        'fields' => ['title', 'subtitle', 'cta_text'],
        'idField' => 'id'
    ],
    'about' => [
        'name' => 'Despre Noi',
        'fields' => ['section_label', 'title', 'content'],
        'idField' => 'id'
    ],
    'services_section' => [
        'name' => 'Secțiune Servicii',
        'fields' => ['section_label', 'title', 'description'],
        'idField' => 'id'
    ],
    'services' => [
        'name' => 'Servicii',
        'fields' => ['title', 'short_description', 'full_description', 'features'],
        'idField' => 'id',
        'displayField' => 'title'
    ],
    'process_section' => [
        'name' => 'Secțiune Proces',
        'fields' => ['section_label', 'title', 'description'],
        'idField' => 'id'
    ],
    'process_steps' => [
        'name' => 'Pași Proces',
        'fields' => ['title', 'description', 'features'],
        'idField' => 'id',
        'displayField' => 'title'
    ],
    'faq_section' => [
        'name' => 'Secțiune FAQ',
        'fields' => ['section_label', 'title'],
        'idField' => 'id'
    ],
    'faq' => [
        'name' => 'Întrebări FAQ',
        'fields' => ['question', 'answer'],
        'idField' => 'id',
        'displayField' => 'question'
    ],
    'contact' => [
        'name' => 'Contact',
        'fields' => ['section_label', 'title', 'description', 'form_title'],
        'idField' => 'id'
    ],
    'why_us_items' => [
        'name' => 'De Ce Noi - Elemente',
        'fields' => ['title', 'description'],
        'idField' => 'id',
        'displayField' => 'title'
    ],
    'about_stats' => [
        'name' => 'Statistici Despre Noi',
        'fields' => ['label', 'suffix'],
        'idField' => 'id',
        'displayField' => 'label'
    ],
    'hero_trust_items' => [
        'name' => 'Hero Trust Items',
        'fields' => ['text'],
        'idField' => 'id',
        'displayField' => 'text'
    ]
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_message'] = 'Token de securitate invalid!';
        $_SESSION['flash_type'] = 'error';
        header('Location: translations.php');
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save_translations') {
        $table = $_POST['table'] ?? '';
        $recordId = (int)($_POST['record_id'] ?? 0);
        
        if (isset($translatableTables[$table]) && $recordId > 0) {
            try {
                $updates = [];
                $params = [];
                
                foreach ($languages as $langCode => $langName) {
                    foreach ($translatableTables[$table]['fields'] as $field) {
                        $fieldName = $field . '_' . $langCode;
                        if (isset($_POST[$fieldName])) {
                            $updates[] = "$fieldName = ?";
                            $params[] = $_POST[$fieldName];
                        }
                    }
                }
                
                if (!empty($updates)) {
                    // 1) Persist to normalized translation tables used by frontend.
                    if (isset($translationTableMap[$table])) {
                        $target = $translationTableMap[$table];

                        if (translationTableExists($pdo, $tableExistsCache, $target['table'])) {
                            foreach ($languages as $langCode => $langName) {
                                $columns = [];
                                $values = [];

                                foreach ($translatableTables[$table]['fields'] as $field) {
                                    $columns[] = $field;
                                    $values[] = $_POST[$field . '_' . $langCode] ?? null;
                                }

                                $insertColumns = array_merge([$target['fk'], 'language'], $columns);
                                $placeholders = implode(', ', array_fill(0, count($insertColumns), '?'));
                                $updatesSql = implode(', ', array_map(function ($col) {
                                    return "$col = VALUES($col)";
                                }, $columns));

                                $upsertSql = "INSERT INTO {$target['table']} (" . implode(', ', $insertColumns) . ") "
                                    . "VALUES ($placeholders) ON DUPLICATE KEY UPDATE $updatesSql";

                                $upsertParams = array_merge([$recordId, $langCode], $values);
                                $upsertStmt = $pdo->prepare($upsertSql);
                                $upsertStmt->execute($upsertParams);
                            }
                        }
                    }

                    // 2) Persist to legacy suffix columns only when they exist (safe compatibility layer).
                    $existingColumns = getTableColumns($pdo, $tableColumnsCache, $table);
                    $legacyUpdates = [];
                    $legacyParams = [];

                    foreach ($languages as $langCode => $langName) {
                        foreach ($translatableTables[$table]['fields'] as $field) {
                            $fieldName = $field . '_' . $langCode;
                            if (isset($existingColumns[$fieldName]) && isset($_POST[$fieldName])) {
                                $legacyUpdates[] = "$fieldName = ?";
                                $legacyParams[] = $_POST[$fieldName];
                            }
                        }
                    }

                    if (!empty($legacyUpdates)) {
                        $legacyParams[] = $recordId;
                        $sql = "UPDATE $table SET " . implode(', ', $legacyUpdates) . " WHERE {$translatableTables[$table]['idField']} = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($legacyParams);
                    }
                    
                    $_SESSION['flash_message'] = 'Traducerile au fost salvate!';
                    $_SESSION['flash_type'] = 'success';
                }
            } catch (PDOException $e) {
                $_SESSION['flash_message'] = 'Eroare: ' . $e->getMessage();
                $_SESSION['flash_type'] = 'error';
            }
        }
        
        header('Location: translations.php?table=' . urlencode($table) . '&id=' . $recordId);
        exit;
    }
}

// Get selected table and record
$selectedTable = $_GET['table'] ?? 'services';
$selectedId = (int)($_GET['id'] ?? 0);

// Get records for selected table
$records = [];
$currentRecord = null;
if (isset($translatableTables[$selectedTable])) {
    try {
        $displayField = $translatableTables[$selectedTable]['displayField'] ?? 'id';
        $idField = $translatableTables[$selectedTable]['idField'];
        $records = $pdo->query("SELECT * FROM $selectedTable ORDER BY $idField")->fetchAll(PDO::FETCH_ASSOC);
        
        if ($selectedId > 0) {
            foreach ($records as $record) {
                if ($record[$idField] == $selectedId) {
                    $currentRecord = $record;
                    break;
                }
            }
        } elseif (!empty($records)) {
            $currentRecord = $records[0];
            $selectedId = $currentRecord[$idField];
        }

        // Hydrate current record with values from normalized translation tables
        // so existing website translations are editable from dashboard.
        if ($currentRecord && isset($translationTableMap[$selectedTable])) {
            $target = $translationTableMap[$selectedTable];
            if (translationTableExists($pdo, $tableExistsCache, $target['table'])) {
                $translationRowsStmt = $pdo->prepare(
                    "SELECT * FROM {$target['table']} WHERE {$target['fk']} = ?"
                );
                $translationRowsStmt->execute([$selectedId]);
                $translationRows = $translationRowsStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($translationRows as $translationRow) {
                    $langCode = $translationRow['language'] ?? '';
                    if (!isset($languages[$langCode])) {
                        continue;
                    }

                    foreach ($translatableTables[$selectedTable]['fields'] as $field) {
                        $virtualField = $field . '_' . $langCode;
                        $value = $translationRow[$field] ?? null;
                        if ($value !== null && $value !== '') {
                            $currentRecord[$virtualField] = $value;
                        }
                    }
                }
            }
        }
    } catch (PDOException $e) {
        $records = [];
    }
}

include '../includes/header.php';
?>

<style>
.translations-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 1.5rem;
}

.translations-sidebar {
    background: var(--admin-white);
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.table-selector {
    margin-bottom: 1rem;
}

.table-selector select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--admin-gray-300);
    border-radius: 4px;
    background: var(--admin-white);
    font-family: inherit;
    font-size: 14px;
}

.table-selector select:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
}

.record-list {
    max-height: 400px;
    overflow-y: auto;
}

.record-item {
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.record-item:hover {
    background: var(--admin-gray-100);
}

.record-item.active {
    background: var(--admin-primary);
    color: white;
}

.translations-main {
    background: var(--admin-white);
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.translation-group {
    margin-bottom: 2rem;
    padding: 1rem;
    background: var(--admin-gray-50);
    border-radius: 8px;
}

.translation-group h4 {
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--admin-gray-900);
}

.lang-flag {
    font-size: 1.2rem;
}

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.field-group {
    margin-bottom: 1rem;
}

.field-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
    color: var(--admin-gray-600);
}

.field-group input,
.field-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--admin-gray-300);
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
    background: var(--admin-white);
}

.field-group input:focus,
.field-group textarea:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
}

.field-group textarea {
    min-height: 100px;
    resize: vertical;
}

.original-value {
    background: #fff3cd;
    border-color: #ffc107;
}

.translation-value {
    background: #d4edda;
    border-color: #28a745;
}

.translation-value:placeholder-shown {
    background: #f8d7da;
    border-color: #dc3545;
}

.field-hint {
    font-size: 0.75rem;
    color: var(--admin-gray-500);
    margin-top: 0.25rem;
}

.btn-save {
    background: var(--admin-primary);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-save:hover {
    background: var(--admin-primary-dark);
}

@media (max-width: 768px) {
    .translations-container {
        grid-template-columns: 1fr;
    }
    
    .field-row {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="page-header">
    <h1>Traduceri</h1>
    <p>Gestionați traducerile pentru conținutul site-ului în RU și EN</p>
</div>

<div class="translations-container">
    <div class="translations-sidebar">
        <div class="table-selector">
            <label><strong>Secțiune:</strong></label>
            <select id="tableSelector" onchange="changeTable(this.value)">
                <?php foreach ($translatableTables as $tableName => $tableConfig): ?>
                <option value="<?php echo $tableName; ?>" <?php echo $selectedTable === $tableName ? 'selected' : ''; ?>>
                    <?php echo e($tableConfig['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="record-list">
            <?php if (!empty($records)): ?>
                <?php 
                $displayField = $translatableTables[$selectedTable]['displayField'] ?? 'id';
                $idField = $translatableTables[$selectedTable]['idField'];
                foreach ($records as $record): 
                    $displayValue = $record[$displayField] ?? 'ID: ' . $record[$idField];
                    if (strlen($displayValue) > 40) {
                        $displayValue = substr($displayValue, 0, 40) . '...';
                    }
                ?>
                <a href="translations.php?table=<?php echo urlencode($selectedTable); ?>&id=<?php echo $record[$idField]; ?>" 
                   class="record-item <?php echo $record[$idField] == $selectedId ? 'active' : ''; ?>"
                   style="text-decoration: none; color: inherit; display: block;">
                    <?php echo e($displayValue); ?>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--gray-500); padding: 0.5rem;">Nu există înregistrări</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="translations-main">
        <?php if ($currentRecord): ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="save_translations">
            <input type="hidden" name="table" value="<?php echo e($selectedTable); ?>">
            <input type="hidden" name="record_id" value="<?php echo $selectedId; ?>">
            
            <h3 style="margin-bottom: 1rem;">
                <?php echo e($translatableTables[$selectedTable]['name']); ?>
                <?php if (isset($translatableTables[$selectedTable]['displayField'])): ?>
                    - <?php echo e($currentRecord[$translatableTables[$selectedTable]['displayField']] ?? ''); ?>
                <?php endif; ?>
            </h3>
            
            <?php foreach ($translatableTables[$selectedTable]['fields'] as $field): ?>
            <div class="translation-group">
                <h4><?php echo ucfirst(str_replace('_', ' ', $field)); ?></h4>
                
                <div class="field-group">
                    <label>🇷🇴 Română (Original)</label>
                    <?php if (in_array($field, ['content', 'description', 'full_description', 'answer', 'features'])): ?>
                    <textarea class="original-value" readonly><?php echo e($currentRecord[$field] ?? ''); ?></textarea>
                    <?php else: ?>
                    <input type="text" class="original-value" value="<?php echo e($currentRecord[$field] ?? ''); ?>" readonly>
                    <?php endif; ?>
                    <div class="field-hint">Textul original în română (nu se poate edita aici)</div>
                </div>
                
                <div class="field-row">
                    <div class="field-group">
                        <label>🇷🇺 Русский</label>
                        <?php if (in_array($field, ['content', 'description', 'full_description', 'answer', 'features'])): ?>
                        <textarea name="<?php echo $field; ?>_ru" class="translation-value" 
                                  placeholder="Introduceți traducerea în rusă..."><?php echo e($currentRecord[$field . '_ru'] ?? ''); ?></textarea>
                        <?php else: ?>
                        <input type="text" name="<?php echo $field; ?>_ru" class="translation-value" 
                               value="<?php echo e($currentRecord[$field . '_ru'] ?? ''); ?>"
                               placeholder="Traducere rusă...">
                        <?php endif; ?>
                    </div>
                    
                    <div class="field-group">
                        <label>🇬🇧 English</label>
                        <?php if (in_array($field, ['content', 'description', 'full_description', 'answer', 'features'])): ?>
                        <textarea name="<?php echo $field; ?>_en" class="translation-value"
                                  placeholder="Enter English translation..."><?php echo e($currentRecord[$field . '_en'] ?? ''); ?></textarea>
                        <?php else: ?>
                        <input type="text" name="<?php echo $field; ?>_en" class="translation-value"
                               value="<?php echo e($currentRecord[$field . '_en'] ?? ''); ?>"
                               placeholder="English translation...">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Salvează Traducerile
            </button>
        </form>
        <?php else: ?>
        <p style="color: var(--gray-500);">Selectați o înregistrare pentru a edita traducerile.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function changeTable(table) {
    window.location.href = 'translations.php?table=' + encodeURIComponent(table);
}

function selectRecord(table, id) {
    window.location.href = 'translations.php?table=' + encodeURIComponent(table) + '&id=' + id;
}
</script>

<?php include '../includes/footer.php'; ?>
