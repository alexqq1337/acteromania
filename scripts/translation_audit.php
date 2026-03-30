<?php
/**
 * Audit traduceri — fișiere lang, chei în cod PHP/JS, tabele *_translations în DB
 *
 * Raportează:
 *   - chei lipsă / goale / identice cu română (probabil netraduse)
 *   - rânduri/câmpuri lipsă în DB pentru ru/en față de conținutul RO
 *   - text identic cu baza română în traducere (limba greșită afișată)
 *
 * Utilizare:
 *   php scripts/translation_audit.php
 *   php scripts/translation_audit.php --no-db     (doar fișiere + cod, fără MySQL)
 *   php scripts/translation_audit.php --json     (ieșire JSON)
 */

declare(strict_types=1);

$root = realpath(__DIR__ . '/..');
if ($root === false) {
    fwrite(STDERR, "Nu găsesc rădăcina proiectului.\n");
    exit(2);
}

$opts = getopt('', ['no-db', 'json', 'help']) ?: [];
if (isset($opts['help'])) {
    echo file_get_contents(__FILE__, false, null, 0, 800) . "\n... (vezi fișierul pentru detalii)\n";
    exit(0);
}

$jsonOut = isset($opts['json']);
$skipDb = isset($opts['no-db']);

$langCodes = ['ro', 'ru', 'en'];
$langFiles = [
    'ro' => $root . '/lang/ro.php',
    'ru' => $root . '/lang/ru.php',
    'en' => $root . '/lang/en.php',
];

foreach ($langFiles as $code => $path) {
    if (!is_file($path)) {
        fwrite(STDERR, "Lipsește fișierul de limbă {$code}: {$path}\n");
        exit(2);
    }
}

$langData = [];
foreach ($langFiles as $code => $path) {
    $data = include $path;
    if (!is_array($data)) {
        fwrite(STDERR, "Fișierul nu returnează array: {$path}\n");
        exit(2);
    }
    $langData[$code] = $data;
}

// —— Chei folosite în cod (PHP __ / _e, JS _t) ——
$usedKeys = [];
$excludePathFragments = [
    DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR,
    DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR,
    DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR,
];

$phpRegex = '/(?:__|_e)\(\s*[\'\"]([^\'\"]+)[\'\"]/';
$jsRegex = '/_t\(\s*[\'\"]([^\'\"]+)[\'\"]/';

$iter = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

foreach ($iter as $fileInfo) {
    if (!$fileInfo->isFile()) {
        continue;
    }
    $path = $fileInfo->getPathname();
    foreach ($excludePathFragments as $frag) {
        if (strpos($path, $frag) !== false) {
            continue 2;
        }
    }

    $ext = strtolower($fileInfo->getExtension());
    if ($ext === 'php') {
        $content = @file_get_contents($path);
        if ($content === false) {
            continue;
        }
        if (preg_match_all($phpRegex, $content, $m)) {
            foreach ($m[1] as $key) {
                if ($key !== '') {
                    $usedKeys[$key] = true;
                }
            }
        }
    } elseif ($ext === 'js') {
        $content = @file_get_contents($path);
        if ($content === false) {
            continue;
        }
        if (preg_match_all($jsRegex, $content, $m)) {
            foreach ($m[1] as $key) {
                if ($key !== '') {
                    $usedKeys[$key] = true;
                }
            }
        }
    }
}

ksort($usedKeys);
$usedKeyList = array_keys($usedKeys);

// —— Raport fișiere lang ——
$fileReport = [
    'used_keys_count' => count($usedKeyList),
    'by_language' => [],
];

$roMap = $langData['ro'];
$hasFileIssues = false;

foreach (['ru', 'en'] as $lc) {
    $map = $langData[$lc];
    $entry = [
        'missing_keys' => [],
        'empty_values' => [],
        'same_as_romanian' => [],
        'equals_key' => [],
        'unused_keys' => [],
    ];

    foreach ($usedKeyList as $key) {
        if (!array_key_exists($key, $map)) {
            $entry['missing_keys'][] = $key;
            $hasFileIssues = true;
            continue;
        }
        $val = $map[$key];
        if (!is_string($val)) {
            continue;
        }
        if (trim($val) === '') {
            $entry['empty_values'][] = $key;
            $hasFileIssues = true;
        }
        if ($val === $key) {
            $entry['equals_key'][] = $key;
            $hasFileIssues = true;
        }
        $roVal = $roMap[$key] ?? null;
        if (is_string($roVal) && trim($roVal) !== '' && trim($val) === trim($roVal)) {
            $entry['same_as_romanian'][] = $key;
            $hasFileIssues = true;
        }
    }

    foreach (array_keys($map) as $key) {
        if (!isset($usedKeys[$key])) {
            $entry['unused_keys'][] = $key;
        }
    }
    sort($entry['unused_keys']);
    $fileReport['by_language'][$lc] = $entry;
}

// Chei lipsă în română (folosite în cod dar nu în ro.php)
$fileReport['missing_in_romanian'] = [];
foreach ($usedKeyList as $key) {
    if (!array_key_exists($key, $roMap)) {
        $fileReport['missing_in_romanian'][] = $key;
        $hasFileIssues = true;
    }
}

// —— Baza de date ——
$dbReport = ['connected' => false, 'entities' => [], 'error' => null];

if (!$skipDb) {
    $dbHost = 'localhost';
    $dbName = 'acteromania_cms';
    $dbUser = 'root';
    $dbPass = '';
    $dbCharset = 'utf8mb4';

    $cfgPath = $root . '/config.php';
    if (is_readable($cfgPath)) {
        $cfg = file_get_contents($cfgPath);
        if (preg_match("/define\s*\(\s*'DB_HOST'\s*,\s*'([^']*)'\s*\)/", $cfg, $m)) {
            $dbHost = $m[1];
        }
        if (preg_match("/define\s*\(\s*'DB_NAME'\s*,\s*'([^']*)'\s*\)/", $cfg, $m)) {
            $dbName = $m[1];
        }
        if (preg_match("/define\s*\(\s*'DB_USER'\s*,\s*'([^']*)'\s*\)/", $cfg, $m)) {
            $dbUser = $m[1];
        }
        if (preg_match("/define\s*\(\s*'DB_PASS'\s*,\s*'([^']*)'\s*\)/", $cfg, $m)) {
            $dbPass = $m[1];
        }
        if (preg_match("/define\s*\(\s*'DB_CHARSET'\s*,\s*'([^']*)'\s*\)/", $cfg, $m)) {
            $dbCharset = $m[1];
        }
    }

    try {
        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$dbCharset}";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $dbReport['connected'] = true;
        $dbReport['entities'] = auditDatabaseTranslations($pdo);
        foreach ($dbReport['entities'] as $ent) {
            if (!empty($ent['issues'])) {
                $hasFileIssues = true;
            }
        }
    } catch (Throwable $e) {
        $dbReport['error'] = $e->getMessage();
    }
}

if ($jsonOut) {
    echo json_encode(
        ['lang_files' => $fileReport, 'database' => $dbReport],
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    ) . "\n";
    exit($hasFileIssues ? 1 : 0);
}

// —— Text pentru consolă ——
echo "=== Audit traduceri ActeRomânia ===\n\n";
echo "Chei folosite în cod (PHP __/_e, JS _t): " . count($usedKeyList) . "\n\n";

if (!empty($fileReport['missing_in_romanian'])) {
    echo "!!! CRITIC: chei folosite în cod dar LIPSESC din lang/ro.php:\n";
    foreach ($fileReport['missing_in_romanian'] as $k) {
        echo "    - {$k}\n";
    }
    echo "\n";
}

foreach (['ru', 'en'] as $lc) {
    $e = $fileReport['by_language'][$lc];
    $label = strtoupper($lc);
    echo "--- Fișier lang/{$lc}.php ---\n";
    if (!empty($e['missing_keys'])) {
        echo "  Lipsă chei (" . count($e['missing_keys']) . "):\n";
        foreach ($e['missing_keys'] as $k) {
            echo "    - {$k}\n";
        }
    }
    if (!empty($e['empty_values'])) {
        echo "  Valori goale (" . count($e['empty_values']) . "):\n";
        foreach ($e['empty_values'] as $k) {
            echo "    - {$k}\n";
        }
    }
    if (!empty($e['same_as_romanian'])) {
        echo "  Identic cu RO — posibil netradus (" . count($e['same_as_romanian']) . "):\n";
        foreach (array_slice($e['same_as_romanian'], 0, 40) as $k) {
            $snippet = mb_substr((string)($roMap[$k] ?? ''), 0, 60);
            echo "    - {$k}  |  " . str_replace(["\n", "\r"], ' ', $snippet) . "…\n";
        }
        if (count($e['same_as_romanian']) > 40) {
            echo "    ... +" . (count($e['same_as_romanian']) - 40) . " altele\n";
        }
    }
    if (!empty($e['equals_key'])) {
        echo "  Valoare = cheie (fallback stricat) (" . count($e['equals_key']) . "):\n";
        foreach ($e['equals_key'] as $k) {
            echo "    - {$k}\n";
        }
    }
    echo "  Chei nefolosite în cod: " . count($e['unused_keys']) . " (informativ)\n\n";
}

if ($skipDb) {
    echo "Mod --no-db: verificarea MySQL a fost sărită.\n";
} elseif (!$dbReport['connected']) {
    echo "--- Baza de date ---\n";
    echo "  Nu mă pot conecta: " . ($dbReport['error'] ?? 'necunoscut') . "\n";
    echo "  (rulează fără --no-db când MySQL rulează, sau folosește XAMPP)\n\n";
} else {
    echo "--- Baza de date (conținut CMS vs traduceri ru/en) ---\n";
    foreach ($dbReport['entities'] as $ent) {
        $name = $ent['label'];
        if (empty($ent['issues'])) {
            echo "  [OK] {$name}\n";
            continue;
        }
        echo "  [ATENȚIE] {$name}\n";
        foreach ($ent['issues'] as $issue) {
            echo "    - {$issue}\n";
        }
    }
    echo "\n";
}

echo "Notă: cheile PHP transmise ca variabile nu pot fi detectate static.\n";
echo "Hero / servicii / FAQ din pagină vin din DB; inconsistențele apar mai sus.\n";

exit($hasFileIssues ? 1 : 0);

// ——————————————————————————————————————————————————————————————

/**
 * @return list<array{label:string, issues:list<string>}>
 */
function auditDatabaseTranslations(PDO $pdo): array
{
    $entities = [
        ['label' => 'hero', 'base' => 'hero', 'trans' => 'hero_translations', 'fk' => 'hero_id', 'filter' => null],
        ['label' => 'hero_trust_items', 'base' => 'hero_trust_items', 'trans' => 'hero_trust_items_translations', 'fk' => 'trust_item_id', 'filter' => 'enabled = 1'],
        ['label' => 'about', 'base' => 'about', 'trans' => 'about_translations', 'fk' => 'about_id', 'filter' => null],
        ['label' => 'about_stats', 'base' => 'about_stats', 'trans' => 'about_stats_translations', 'fk' => 'stat_id', 'filter' => 'enabled = 1'],
        ['label' => 'services_section', 'base' => 'services_section', 'trans' => 'services_section_translations', 'fk' => 'section_id', 'filter' => null],
        ['label' => 'services', 'base' => 'services', 'trans' => 'services_translations', 'fk' => 'service_id', 'filter' => 'enabled = 1'],
        ['label' => 'process_section', 'base' => 'process_section', 'trans' => 'process_section_translations', 'fk' => 'section_id', 'filter' => null],
        ['label' => 'process_steps', 'base' => 'process_steps', 'trans' => 'process_steps_translations', 'fk' => 'step_id', 'filter' => 'enabled = 1'],
        ['label' => 'faq_section', 'base' => 'faq_section', 'trans' => 'faq_section_translations', 'fk' => 'section_id', 'filter' => null],
        ['label' => 'faq', 'base' => 'faq', 'trans' => 'faq_translations', 'fk' => 'faq_id', 'filter' => 'enabled = 1'],
        ['label' => 'contact', 'base' => 'contact', 'trans' => 'contact_translations', 'fk' => 'contact_id', 'filter' => null],
        ['label' => 'why_us_items', 'base' => 'why_us_items', 'trans' => 'why_us_items_translations', 'fk' => 'item_id', 'filter' => 'enabled = 1'],
    ];

    $out = [];
    $langs = ['ru', 'en'];

    foreach ($entities as $spec) {
        $issues = [];
        $base = $spec['base'];
        $trans = $spec['trans'];
        $fk = $spec['fk'];

        if (!tableExists($pdo, $base) || !tableExists($pdo, $trans)) {
            $out[] = ['label' => $spec['label'], 'issues' => ["Lipsește tabela {$base} sau {$trans}"]];
            continue;
        }

        $textCols = getOverlappingTextColumns($pdo, $base, $trans, $fk);
        if ($textCols === []) {
            $out[] = ['label' => $spec['label'], 'issues' => ['Nu am găsit coloane text comune pentru audit']];
            continue;
        }

        $sql = "SELECT * FROM `{$base}`";
        if (!empty($spec['filter'])) {
            $sql .= ' WHERE ' . $spec['filter'];
        }
        $rows = $pdo->query($sql)->fetchAll();
        if ($rows === []) {
            $out[] = ['label' => $spec['label'], 'issues' => []];
            continue;
        }

        $pk = guessPrimaryKey($rows[0]);

        foreach ($rows as $row) {
            $id = $row[$pk] ?? null;
            if ($id === null) {
                continue;
            }

            foreach ($langs as $lang) {
                $stmt = $pdo->prepare("SELECT * FROM `{$trans}` WHERE `{$fk}` = ? AND `language` = ? LIMIT 1");
                $stmt->execute([$id, $lang]);
                $trow = $stmt->fetch();

                foreach ($textCols as $col) {
                    $baseVal = isset($row[$col]) ? trim((string) $row[$col]) : '';
                    if ($baseVal === '') {
                        continue;
                    }

                    if ($trow === false) {
                        $issues[] = "{$lang}: lipsește rând traducere pentru {$spec['label']} id={$id}";
                        break;
                    }

                    $trVal = isset($trow[$col]) ? trim((string) $trow[$col]) : '';
                    if ($trVal === '') {
                        $issues[] = "{$lang}: câmp gol `{$col}` (afișare fallback RO) — {$spec['label']} id={$id}";
                    } elseif ($trVal === $baseVal) {
                        $issues[] = "{$lang}: `{$col}` identic cu textul român — {$spec['label']} id={$id} (verifică dacă e intenționat)";
                    }
                }
            }
        }

        $out[] = ['label' => $spec['label'], 'issues' => array_values(array_unique($issues))];
    }

    return $out;
}

function tableExists(PDO $pdo, string $table): bool
{
    $st = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?'
    );
    $st->execute([$table]);

    return (int) $st->fetchColumn() > 0;
}

/**
 * Coloane text prezente atât în bază cât și în tabela de traduceri (excl. id, fk, language, timestamps).
 *
 * @return list<string>
 */
function getOverlappingTextColumns(PDO $pdo, string $baseTable, string $transTable, string $fk): array
{
    $skip = ['id', $fk, 'language', 'created_at', 'updated_at'];

    $st = $pdo->prepare(
        'SELECT COLUMN_NAME, DATA_TYPE FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
    );
    $st->execute([$transTable]);
    $transCols = [];
    while ($r = $st->fetch()) {
        if (in_array($r['COLUMN_NAME'], $skip, true)) {
            continue;
        }
        if (!isTextualType($r['DATA_TYPE'])) {
            continue;
        }
        $transCols[$r['COLUMN_NAME']] = true;
    }

    $st->execute([$baseTable]);
    $baseCols = [];
    while ($r = $st->fetch()) {
        if (in_array($r['COLUMN_NAME'], $skip, true)) {
            continue;
        }
        if (!isTextualType($r['DATA_TYPE'])) {
            continue;
        }
        $baseCols[$r['COLUMN_NAME']] = true;
    }

    $overlap = array_intersect_key($transCols, $baseCols);

    return array_keys($overlap);
}

function isTextualType(string $dataType): bool
{
    return in_array(strtolower($dataType), ['varchar', 'text', 'mediumtext', 'longtext', 'char', 'json'], true);
}

/** @param array<string,mixed> $row */
function guessPrimaryKey(array $row): string
{
    if (array_key_exists('id', $row)) {
        return 'id';
    }
    foreach (['section_id', 'service_id', 'faq_id', 'step_id', 'stat_id', 'trust_item_id', 'item_id', 'contact_id', 'hero_id', 'about_id'] as $c) {
        if (array_key_exists($c, $row)) {
            return $c;
        }
    }

    return 'id';
}
