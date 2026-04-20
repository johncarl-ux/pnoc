<?php
header('Content-Type: application/json; charset=utf-8');

function respond(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

function parseJsonBody(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function normalizeSource(string $source): ?string
{
    $value = strtolower(trim($source));
    if ($value === 'bentaco') return 'BENTACO';
    if ($value === 'iot') return 'IOT';
    if ($value === 'all') return 'ALL';
    return null;
}

function toNullableDateTime($value): ?string
{
    $text = trim((string)($value ?? ''));
    if ($text === '') {
        return null;
    }

    $formats = [
        'Y-m-d H:i:s',
        'Y-m-d H:i',
        'Y-m-d',
        'd M Y, H:i',
        'd M Y, H:i:s',
        'm/d/Y, h:i:s A',
        'm/d/Y, h:i A',
        'm/d/Y',
        'n/j/Y, g:i:s A',
        'n/j/Y, g:i A',
    ];

    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $text);
        if ($date instanceof DateTime) {
            return $date->format('Y-m-d H:i:s');
        }
    }

    $timestamp = strtotime($text);
    if ($timestamp === false) {
        return null;
    }

    return date('Y-m-d H:i:s', $timestamp);
}

function toNullableDate($value): ?string
{
    $dateTime = toNullableDateTime($value);
    if ($dateTime === null) {
        return null;
    }
    return substr($dateTime, 0, 10);
}

function normalizeStatus($value): string
{
    $text = strtolower(trim((string)($value ?? '')));
    if ($text === 'under maintenance' || $text === 'maintenance') {
        return 'Under Maintenance';
    }
    if ($text === 'retired' || $text === 'disposed' || $text === 'damaged' || $text === 'defective' || $text === 'unusable' || $text === 'not usable') {
        return 'Retired';
    }
    return 'Usable';
}

function normalizeTextValue($value): string
{
    return trim((string)($value ?? ''));
}

function isPlaceholderIdentifier(string $value): bool
{
    $text = strtolower(trim($value));
    if ($text === '') {
        return true;
    }
    return in_array($text, ['n/a', 'na', 'none', 'no tag', '-', '--', 'null', 'undefined'], true);
}

function formatLocation(array $row): string
{
    $building = normalizeTextValue($row['building'] ?? '');
    $room = normalizeTextValue($row['room'] ?? '');
    $storageArea = normalizeTextValue($row['storageArea'] ?? '');
    $parts = array_values(array_filter([$building, $room, $storageArea], static fn($part) => $part !== ''));
    if ($parts) {
        return implode(' / ', $parts);
    }
    return normalizeTextValue($row['itemLocation'] ?? '');
}

function parseAmount($value): float
{
    $clean = preg_replace('/[^\d.-]/', '', (string)($value ?? ''));
    if ($clean === null || $clean === '') {
        return 0.0;
    }
    $number = (float)$clean;
    return is_finite($number) ? $number : 0.0;
}

function columnExists(PDO $pdo, string $table, string $column): bool
{
    static $cache = [];
    $key = strtolower($table . '.' . $column);
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $stmt->execute([$table, $column]);
    $cache[$key] = ((int)$stmt->fetchColumn()) > 0;
    return $cache[$key];
}

function historyTableExists(PDO $pdo): bool
{
    static $cache;
    if ($cache !== null) {
        return $cache;
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?');
    $stmt->execute(['inventory_item_history']);
    $cache = ((int)$stmt->fetchColumn()) > 0;
    return $cache;
}

function readExtraData(?string $json): array
{
    if (!is_string($json) || trim($json) === '') {
        return [];
    }
    $decoded = json_decode($json, true);
    return is_array($decoded) ? $decoded : [];
}

function buildExtraData(array $row): array
{
    $coreKeys = [
        'itemId', 'itemNo', 'propertyNumber', 'assetNumber', 'itemDescription', 'building', 'room', 'department',
        'storageArea', 'itemLocation', 'assignedEmployee', 'acquisitionCost', 'mrNo', 'parIcsNumber', 'referenceNumber',
        'itemStatus', 'dateAdded', 'lastUpdated', 'source', 'historyLog', 'createdAt', 'updatedAt'
    ];

    $extra = [];
    foreach ($row as $key => $value) {
        if (!in_array($key, $coreKeys, true)) {
            $extra[$key] = $value;
        }
    }

    if (array_key_exists('allocationType', $row)) $extra['allocationType'] = $row['allocationType'];
    if (array_key_exists('allocatedTo', $row)) $extra['allocatedTo'] = $row['allocatedTo'];
    if (array_key_exists('allocationTo', $row)) $extra['allocationTo'] = $row['allocationTo'];
    if (array_key_exists('allocationDate', $row)) $extra['allocationDate'] = $row['allocationDate'];
    if (array_key_exists('returnDate', $row)) $extra['returnDate'] = $row['returnDate'];
    if (array_key_exists('allocationNotes', $row)) $extra['allocationNotes'] = $row['allocationNotes'];
    if (array_key_exists('mapLocationId', $row)) $extra['mapLocationId'] = $row['mapLocationId'];
    if (array_key_exists('assetCategory', $row)) $extra['assetCategory'] = $row['assetCategory'];
    if (array_key_exists('acquisitionYear', $row)) $extra['acquisitionYear'] = $row['acquisitionYear'];

    return $extra;
}

function naturalKeyFromArray(array $row, string $source): string
{
    $propertyNumber = normalizeTextValue($row['propertyNumber'] ?? '');
    if ($propertyNumber !== '' && !isPlaceholderIdentifier($propertyNumber)) {
        return strtolower($source) . '|property|' . $propertyNumber;
    }

    $assetNumber = normalizeTextValue($row['assetNumber'] ?? '');
    if ($assetNumber !== '' && !isPlaceholderIdentifier($assetNumber) && !preg_match('/^n\/?a$/i', $assetNumber)) {
        return strtolower($source) . '|asset|' . $assetNumber;
    }

    $itemId = normalizeTextValue($row['itemId'] ?? '');
    if ($itemId !== '' && !isPlaceholderIdentifier($itemId)) {
        return strtolower($source) . '|itemid|' . $itemId;
    }

    return strtolower($source) . '|fallback|' . sha1(json_encode([
        $row['itemDescription'] ?? '',
        $row['assignedEmployee'] ?? '',
        $row['itemLocation'] ?? '',
        $row['dateAdded'] ?? ''
    ]));
}

function naturalKeyFromDbRow(array $row, string $source): string
{
    $propertyNumber = normalizeTextValue($row['property_number'] ?? '');
    if ($propertyNumber !== '' && !isPlaceholderIdentifier($propertyNumber)) {
        return strtolower($source) . '|property|' . $propertyNumber;
    }

    $assetNumber = normalizeTextValue($row['asset_number'] ?? '');
    if ($assetNumber !== '' && !isPlaceholderIdentifier($assetNumber) && !preg_match('/^n\/?a$/i', $assetNumber)) {
        return strtolower($source) . '|asset|' . $assetNumber;
    }

    $itemId = normalizeTextValue($row['item_id'] ?? '');
    if ($itemId !== '' && !isPlaceholderIdentifier($itemId)) {
        return strtolower($source) . '|itemid|' . $itemId;
    }

    return strtolower($source) . '|fallback|' . sha1(json_encode([
        $row['item_description'] ?? '',
        $row['assigned_employee'] ?? '',
        $row['item_location'] ?? '',
        $row['date_added'] ?? ''
    ]));
}

function generateItemId(PDO $pdo, string $source): string
{
    $prefix = $source === 'IOT' ? 'IOT-' : 'BEN-';
    $stmt = $pdo->prepare('SELECT item_id FROM inventory_items WHERE item_group = ?');
    $stmt->execute([$source]);
    $max = 0;
    while ($itemId = $stmt->fetchColumn()) {
        if (!is_string($itemId)) {
            continue;
        }
        if (preg_match('/^(?:' . preg_quote($prefix, '/') . ')?(\d{1,})$/', $itemId, $matches)) {
            $max = max($max, (int)$matches[1]);
            continue;
        }
        if (preg_match('/(\d+)$/', $itemId, $matches)) {
            $max = max($max, (int)$matches[1]);
        }
    }

    return $prefix . str_pad((string)($max + 1), 5, '0', STR_PAD_LEFT);
}

function fetchInventoryRows(PDO $pdo, ?string $source = null): array
{
    $sql = 'SELECT id, item_id, item_group, item_no, property_number, asset_number, item_description, building, room, department, storage_area, item_location, assigned_employee, acquisition_cost, reference_number, item_status, date_added, last_updated';
    if (columnExists($pdo, 'inventory_items', 'extra_data')) {
        $sql .= ', extra_data';
    }
    $sql .= ' FROM inventory_items';
    $params = [];
    if ($source !== null) {
        $sql .= ' WHERE item_group = ?';
        $params[] = $source;
    }
    $sql .= ' ORDER BY item_group ASC, COALESCE(item_no, 0) ASC, property_number ASC, id ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetchHistoryMap(PDO $pdo, array $inventoryIds): array
{
    if (!$inventoryIds || !historyTableExists($pdo)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($inventoryIds), '?'));
    $sql = "SELECT inventory_item_id, event_type, event_title, event_details, event_at FROM inventory_item_history WHERE inventory_item_id IN ({$placeholders}) ORDER BY event_at DESC, id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($inventoryIds));

    $historyMap = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = (int)$row['inventory_item_id'];
        if (!isset($historyMap[$id])) {
            $historyMap[$id] = [];
        }
        $historyMap[$id][] = [
            'type' => $row['event_type'],
            'title' => $row['event_title'],
            'details' => $row['event_details'],
            'at' => $row['event_at'],
        ];
    }

    return $historyMap;
}

function rowToResponse(array $row, array $history = []): array
{
    $source = strtoupper((string)($row['item_group'] ?? ''));
    $extra = columnExistsDatabaseSafe($row, 'extra_data') ? readExtraData($row['extra_data'] ?? null) : [];
    $itemLocation = normalizeTextValue($row['item_location'] ?? '') ?: formatLocation([
        'building' => $row['building'] ?? '',
        'room' => $row['room'] ?? '',
        'storageArea' => $row['storage_area'] ?? ''
    ]);
    $referenceNumber = normalizeTextValue($row['reference_number'] ?? '');

    $response = [
        'id' => (int)$row['id'],
        'itemId' => normalizeTextValue($row['item_id'] ?? ''),
        'itemNo' => $row['item_no'] !== null ? (int)$row['item_no'] : null,
        'propertyNumber' => normalizeTextValue($row['property_number'] ?? ''),
        'assetNumber' => normalizeTextValue($row['asset_number'] ?? ''),
        'itemDescription' => normalizeTextValue($row['item_description'] ?? ''),
        'building' => normalizeTextValue($row['building'] ?? ''),
        'room' => normalizeTextValue($row['room'] ?? ''),
        'department' => normalizeTextValue($row['department'] ?? ''),
        'storageArea' => normalizeTextValue($row['storage_area'] ?? ''),
        'itemLocation' => $itemLocation,
        'assignedEmployee' => normalizeTextValue($row['assigned_employee'] ?? ''),
        'acquisitionCost' => (float)($row['acquisition_cost'] ?? 0),
        'referenceNumber' => $referenceNumber,
        'itemStatus' => normalizeStatus($row['item_status'] ?? 'Usable'),
        'dateAdded' => normalizeTextValue($row['date_added'] ?? ''),
        'lastUpdated' => normalizeTextValue($row['last_updated'] ?? ''),
        'source' => $source,
        'historyLog' => $history,
    ];

    if ($source === 'BENTACO') {
        $response['mrNo'] = $referenceNumber;
    }
    if ($source === 'IOT') {
        $response['parIcsNumber'] = $referenceNumber;
    }

    foreach ($extra as $key => $value) {
        if (!array_key_exists($key, $response)) {
            $response[$key] = $value;
        }
    }

    // Keep these aliases available even if they were stored in extra_data.
    $response['mrNo'] = $response['mrNo'] ?? $referenceNumber;
    $response['parIcsNumber'] = $response['parIcsNumber'] ?? $referenceNumber;

    return $response;
}

function columnExistsDatabaseSafe(array $row, string $column): bool
{
    return array_key_exists($column, $row);
}

function syncHistory(PDO $pdo, int $inventoryId, array $historyLog): void
{
    if (!historyTableExists($pdo)) {
        return;
    }

    $deleteStmt = $pdo->prepare('DELETE FROM inventory_item_history WHERE inventory_item_id = ?');
    $deleteStmt->execute([$inventoryId]);

    if (!$historyLog) {
        $historyLog = [[
            'type' => 'update',
            'title' => 'Inventory record saved',
            'details' => 'Record synchronized from the inventory page.',
            'at' => date('Y-m-d H:i:s'),
        ]];
    }

    $insertStmt = $pdo->prepare('INSERT INTO inventory_item_history (inventory_item_id, event_type, event_title, event_details, event_at) VALUES (?, ?, ?, ?, ?)');
    foreach ($historyLog as $entry) {
        if (!is_array($entry)) {
            continue;
        }
        $eventType = normalizeTextValue($entry['type'] ?? 'update') ?: 'update';
        $eventTitle = normalizeTextValue($entry['title'] ?? 'Inventory record saved') ?: 'Inventory record saved';
        $eventDetails = normalizeTextValue($entry['details'] ?? '');
        $eventAt = toNullableDateTime($entry['at'] ?? '') ?? date('Y-m-d H:i:s');
        $insertStmt->execute([$inventoryId, $eventType, $eventTitle, $eventDetails, $eventAt]);
    }
}

function upsertSnapshot(PDO $pdo, string $source, array $rows): int
{
    $existingRows = fetchInventoryRows($pdo, $source);
    $existingMap = [];
    $usedItemIds = [];
    $usedGroupProperties = [];
    foreach ($existingRows as $row) {
        $existingMap[naturalKeyFromDbRow($row, $source)] = $row;
        $existingItemId = normalizeTextValue($row['item_id'] ?? '');
        if ($existingItemId !== '') {
            $usedItemIds[strtolower($existingItemId)] = (int)$row['id'];
        }
        $existingProperty = normalizeTextValue($row['property_number'] ?? '');
        if ($existingProperty !== '') {
            $usedGroupProperties[strtolower($source . '|' . $existingProperty)] = (int)$row['id'];
        }
    }

    $hasExtraData = columnExists($pdo, 'inventory_items', 'extra_data');
    $now = date('Y-m-d H:i:s');
    $seenKeys = [];
    $saved = 0;

    $pdo->beginTransaction();
    try {
        $baseInsertColumns = [
            'item_id', 'item_group', 'item_no', 'property_number', 'asset_number', 'item_description',
            'building', 'room', 'department', 'storage_area', 'item_location', 'assigned_employee',
            'acquisition_cost', 'reference_number', 'item_status', 'date_added', 'last_updated'
        ];
        if ($hasExtraData) {
            $baseInsertColumns[] = 'extra_data';
        }

        foreach ($rows as $index => $inputRow) {
            if (!is_array($inputRow)) {
                continue;
            }

            $sourceRow = normalizeSource((string)($inputRow['source'] ?? $source)) ?? $source;
            if ($sourceRow !== $source) {
                $sourceRow = $source;
            }

            $propertyNumber = normalizeTextValue($inputRow['propertyNumber'] ?? '');
            $assetNumber = normalizeTextValue($inputRow['assetNumber'] ?? '');
            $itemIdInput = normalizeTextValue($inputRow['itemId'] ?? '');
            $naturalKey = naturalKeyFromArray($inputRow, $sourceRow);
            $seenKeys[$naturalKey] = true;
            $existing = $existingMap[$naturalKey] ?? null;

            if (isPlaceholderIdentifier($propertyNumber)) {
                $propertyNumber = '';
            }
            if (isPlaceholderIdentifier($assetNumber)) {
                $assetNumber = '';
            }

            $itemId = $existing['item_id'] ?? ($itemIdInput !== '' ? $itemIdInput : generateItemId($pdo, $sourceRow));
            if (isPlaceholderIdentifier($itemId)) {
                $itemId = generateItemId($pdo, $sourceRow);
            }

            if (!$existing) {
                $baseItemId = $itemId;
                $suffix = 2;
                while (isset($usedItemIds[strtolower($itemId)])) {
                    $trimmedBase = substr($baseItemId, 0, 24);
                    $itemId = $trimmedBase . '-D' . $suffix;
                    $suffix++;
                }
            }
            $usedItemIds[strtolower($itemId)] = $existing ? (int)$existing['id'] : -($index + 1);

            if ($propertyNumber === '' || isPlaceholderIdentifier($propertyNumber)) {
                $propertyNumber = $assetNumber;
            }
            if ($propertyNumber === '' || isPlaceholderIdentifier($propertyNumber)) {
                $propertyNumber = $itemId;
            }

            $existingId = $existing ? (int)$existing['id'] : null;
            $propertyKey = strtolower($sourceRow . '|' . $propertyNumber);
            if (!$existing || (isset($usedGroupProperties[$propertyKey]) && $usedGroupProperties[$propertyKey] !== $existingId)) {
                $baseProperty = $propertyNumber;
                $suffix = 2;
                while (isset($usedGroupProperties[strtolower($sourceRow . '|' . $propertyNumber)])
                    && $usedGroupProperties[strtolower($sourceRow . '|' . $propertyNumber)] !== $existingId) {
                    $propertyNumber = substr($baseProperty, 0, 100) . '-D' . $suffix;
                    $suffix++;
                }
            }
            $usedGroupProperties[strtolower($sourceRow . '|' . $propertyNumber)] = $existingId ?? -($index + 1);

            $itemNo = null;
            $itemNoRaw = $inputRow['itemNo'] ?? null;
            if ($itemNoRaw !== null && $itemNoRaw !== '') {
                $parsedItemNo = (int)preg_replace('/[^\d]/', '', (string)$itemNoRaw);
                if ($parsedItemNo > 0) {
                    $itemNo = $parsedItemNo;
                }
            } elseif ($existing && isset($existing['item_no']) && $existing['item_no'] !== null) {
                $itemNo = (int)$existing['item_no'];
            }

            $referenceNumber = normalizeTextValue($inputRow['referenceNumber'] ?? $inputRow['mrNo'] ?? $inputRow['parIcsNumber'] ?? '');
            $itemLocation = normalizeTextValue($inputRow['itemLocation'] ?? '');
            if ($itemLocation === '') {
                $itemLocation = formatLocation($inputRow);
            }

            $rowData = [
                'item_id' => $itemId,
                'item_group' => $sourceRow,
                'item_no' => $itemNo,
                'property_number' => $propertyNumber,
                'asset_number' => $assetNumber,
                'item_description' => normalizeTextValue($inputRow['itemDescription'] ?? ''),
                'building' => normalizeTextValue($inputRow['building'] ?? ''),
                'room' => normalizeTextValue($inputRow['room'] ?? ''),
                'department' => normalizeTextValue($inputRow['department'] ?? ''),
                'storage_area' => normalizeTextValue($inputRow['storageArea'] ?? ''),
                'item_location' => $itemLocation,
                'assigned_employee' => normalizeTextValue($inputRow['assignedEmployee'] ?? ''),
                'acquisition_cost' => parseAmount($inputRow['acquisitionCost'] ?? 0),
                'reference_number' => $referenceNumber,
                'item_status' => normalizeStatus($inputRow['itemStatus'] ?? 'Usable'),
                'date_added' => toNullableDateTime($inputRow['dateAdded'] ?? null) ?? $now,
                'last_updated' => toNullableDateTime($inputRow['lastUpdated'] ?? null) ?? $now,
            ];

            $extraData = buildExtraData($inputRow);
            unset($extraData['source'], $extraData['historyLog'], $extraData['mrNo'], $extraData['parIcsNumber'], $extraData['referenceNumber']);
            if ($hasExtraData) {
                $rowData['extra_data'] = json_encode($extraData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }

            if ($existing) {
                $updateColumns = [
                    'item_no = ?', 'property_number = ?', 'asset_number = ?', 'item_description = ?', 'building = ?',
                    'room = ?', 'department = ?', 'storage_area = ?', 'item_location = ?', 'assigned_employee = ?',
                    'acquisition_cost = ?', 'reference_number = ?', 'item_status = ?', 'date_added = ?', 'last_updated = ?'
                ];
                $updateValues = [
                    $rowData['item_no'], $rowData['property_number'], $rowData['asset_number'], $rowData['item_description'],
                    $rowData['building'], $rowData['room'], $rowData['department'], $rowData['storage_area'],
                    $rowData['item_location'], $rowData['assigned_employee'], $rowData['acquisition_cost'],
                    $rowData['reference_number'], $rowData['item_status'], $rowData['date_added'], $rowData['last_updated']
                ];
                if ($hasExtraData) {
                    $updateColumns[] = 'extra_data = ?';
                    $updateValues[] = $rowData['extra_data'];
                }
                $updateValues[] = (int)$existing['id'];
                $sql = 'UPDATE inventory_items SET ' . implode(', ', $updateColumns) . ' WHERE id = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute($updateValues);
                $inventoryId = (int)$existing['id'];
            } else {
                $insertColumns = $baseInsertColumns;
                $insertPlaceholders = array_fill(0, count($insertColumns), '?');
                $insertValues = [
                    $rowData['item_id'], $rowData['item_group'], $rowData['item_no'], $rowData['property_number'],
                    $rowData['asset_number'], $rowData['item_description'], $rowData['building'], $rowData['room'],
                    $rowData['department'], $rowData['storage_area'], $rowData['item_location'], $rowData['assigned_employee'],
                    $rowData['acquisition_cost'], $rowData['reference_number'], $rowData['item_status'],
                    $rowData['date_added'], $rowData['last_updated']
                ];
                if ($hasExtraData) {
                    $insertValues[] = $rowData['extra_data'];
                }

                $sql = 'INSERT INTO inventory_items (' . implode(', ', $insertColumns) . ') VALUES (' . implode(', ', $insertPlaceholders) . ')';
                $stmt = $pdo->prepare($sql);
                $stmt->execute($insertValues);
                $inventoryId = (int)$pdo->lastInsertId();
            }

            syncHistory($pdo, $inventoryId, is_array($inputRow['historyLog'] ?? null) ? $inputRow['historyLog'] : []);
            $saved++;
        }

        foreach ($existingMap as $existingKey => $existingRow) {
            if (!isset($seenKeys[$existingKey])) {
                $deleteStmt = $pdo->prepare('DELETE FROM inventory_items WHERE id = ?');
                $deleteStmt->execute([(int)$existingRow['id']]);
            }
        }

        $pdo->commit();
        return $saved;
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $error;
    }
}

$host = '127.0.0.1';
$dbName = 'pnoc_db';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Throwable $error) {
    respond(500, [
        'success' => false,
        'error' => 'Database connection failed. Import pnoc_schema.sql and verify MySQL credentials.',
    ]);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        $sourceParam = normalizeSource((string)($_GET['source'] ?? 'all')) ?? 'ALL';
        $rows = [];
        if ($sourceParam === 'ALL') {
            $rows = fetchInventoryRows($pdo, null);
        } else {
            $rows = fetchInventoryRows($pdo, $sourceParam);
        }

        $historyMap = fetchHistoryMap($pdo, array_map(static fn(array $row) => (int)$row['id'], $rows));
        $data = array_map(static function (array $row) use ($historyMap): array {
            return rowToResponse($row, $historyMap[(int)$row['id']] ?? []);
        }, $rows);

        respond(200, [
            'success' => true,
            'rows' => $data,
        ]);
    }

    if ($method === 'POST') {
        $input = parseJsonBody();
        $source = normalizeSource((string)($input['source'] ?? ''));
        if ($source === null || $source === 'ALL') {
            respond(422, [
                'success' => false,
                'error' => 'Valid source is required. Use bentaco or iot.',
            ]);
        }

        $rows = $input['rows'] ?? null;
        if (!is_array($rows)) {
            respond(422, [
                'success' => false,
                'error' => 'Rows payload must be an array.',
            ]);
        }

        $savedCount = upsertSnapshot($pdo, $source, $rows);
        respond(200, [
            'success' => true,
            'message' => sprintf('%s inventory synchronized.', $source),
            'count' => $savedCount,
        ]);
    }

    respond(405, [
        'success' => false,
        'error' => 'Method not allowed.',
    ]);
} catch (Throwable $error) {
    respond(500, [
        'success' => false,
        'error' => 'Server error: ' . $error->getMessage(),
    ]);
}
