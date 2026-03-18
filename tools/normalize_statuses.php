<?php
// CLI script: normalize statuses in api/data JSON files
// Usage: php tools/normalize_statuses.php

$files = glob(__DIR__ . '/../api/data/*.json');
if (!$files) {
    echo "No data files found in api/data\n";
    exit(0);
}

$changedFiles = 0;
foreach ($files as $file) {
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    if (!is_array($data)) continue;
    $changed = false;
    foreach ($data as &$row) {
        if (!isset($row['itemStatus'])) continue;
        $s = trim(strtolower($row['itemStatus']));
        if (in_array($s, ['damaged','defective','unusable','not usable'], true)) {
            if ($row['itemStatus'] !== 'Retired') {
                $row['itemStatus'] = 'Retired';
                $changed = true;
            }
        }
    }
    if ($changed) {
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "Updated: $file\n";
        $changedFiles++;
    } else {
        echo "No changes: $file\n";
    }
}

echo "Done. Files changed: $changedFiles\n";

?>
