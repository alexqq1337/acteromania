<?php
/**
 * i18n coverage checker
 *
 * Usage:
 *   php scripts/check_i18n.php
 */

$root = realpath(__DIR__ . '/..');
if ($root === false) {
    fwrite(STDERR, "Cannot resolve project root\n");
    exit(2);
}

$langFiles = [
    'ro' => $root . '/lang/ro.php',
    'ru' => $root . '/lang/ru.php',
    'en' => $root . '/lang/en.php',
];

foreach ($langFiles as $code => $path) {
    if (!is_file($path)) {
        fwrite(STDERR, "Missing language file for {$code}: {$path}\n");
        exit(2);
    }
}

$usedKeys = [];
$phpFiles = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);



$excludeParts = [
    DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR,
    DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR,
    DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR,
];

$regex = '/(?:__|_e)\(\s*[\'\"]([^\'\"]+)[\'\"]/';

foreach ($phpFiles as $fileInfo) {
    /** @var SplFileInfo $fileInfo */
    if (!$fileInfo->isFile() || strtolower($fileInfo->getExtension()) !== 'php') {
        continue;
    }

    $path = $fileInfo->getPathname();
    $skip = false;
    foreach ($excludeParts as $part) {
        if (strpos($path, $part) !== false) {
            $skip = true;
            break;
        }
    }
    if ($skip) {
        continue;
    }

    $content = file_get_contents($path);
    if ($content === false) {
        continue;
    }

    if (preg_match_all($regex, $content, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[1] as $match) {
            $key = $match[0];
            if ($key === '') {
                continue;
            }
            $usedKeys[$key] = true;
        }
    }
}

ksort($usedKeys);
$usedKeyList = array_keys($usedKeys);

$langData = [];
foreach ($langFiles as $code => $path) {
    $data = include $path;
    if (!is_array($data)) {
        fwrite(STDERR, "Language file is not returning an array: {$path}\n");
        exit(2);
    }
    $langData[$code] = $data;
}

$hasIssues = false;

echo "i18n coverage report\n";
echo "Used keys in code: " . count($usedKeyList) . "\n\n";

foreach ($langData as $code => $map) {
    $missing = [];
    foreach ($usedKeyList as $key) {
        if (!array_key_exists($key, $map)) {
            $missing[] = $key;
        }
    }

    $unused = [];
    foreach (array_keys($map) as $key) {
        if (!isset($usedKeys[$key])) {
            $unused[] = $key;
        }
    }

    echo strtoupper($code) . ":\n";
    echo "  Missing keys: " . count($missing) . "\n";
    if (!empty($missing)) {
        $hasIssues = true;
        foreach ($missing as $key) {
            echo "    - {$key}\n";
        }
    }

    echo "  Unused keys: " . count($unused) . "\n";
    echo "\n";
}

if ($hasIssues) {
    exit(1);
}

exit(0);
