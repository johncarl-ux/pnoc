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

function toNullableDate($value): ?string
{
    $text = trim((string)($value ?? ''));
    if ($text === '') {
        return null;
    }

    $date = DateTime::createFromFormat('Y-m-d', $text);
    if ($date && $date->format('Y-m-d') === $text) {
        return $text;
    }

    $timestamp = strtotime($text);
    if ($timestamp === false) {
        return null;
    }

    return date('Y-m-d', $timestamp);
}

function normalizePayload(array $input): array
{
    $revision = isset($input['revisionNumber']) ? (int)$input['revisionNumber'] : 0;
    if ($revision < 0) {
        $revision = 0;
    }

    return [
        'documentNumber' => trim((string)($input['documentNumber'] ?? '')),
        'copyNumber' => trim((string)($input['copyNumber'] ?? '')),
        'copyHolder' => trim((string)($input['copyHolder'] ?? '')),
        'documentTitle' => trim((string)($input['documentTitle'] ?? '')),
        'issuanceDate' => toNullableDate($input['issuanceDate'] ?? ''),
        'revisionNumber' => $revision,
        'retrievalDate' => toNullableDate($input['retrievalDate'] ?? ''),
        'retrievedRevision' => trim((string)($input['retrievedRevision'] ?? '')),
    ];
}

function validateRequired(array $payload): ?string
{
    if ($payload['documentNumber'] === '') {
        return 'Doc Number is required.';
    }
    if ($payload['copyNumber'] === '') {
        return 'Copy Number is required.';
    }
    if ($payload['copyHolder'] === '') {
        return 'Copy Holder is required.';
    }
    if ($payload['documentTitle'] === '') {
        return 'Title is required.';
    }
    if ($payload['issuanceDate'] === null) {
        return 'Issued date is required.';
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    respond(200, ['success' => true]);
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
        $stmt = $pdo->query('SELECT id, document_number, copy_number, copy_holder, document_title, issuance_date, revision_number, retrieval_date, retrieved_revision, created_at, updated_at FROM staff_records ORDER BY id DESC');
        $rows = $stmt->fetchAll();

        $data = array_map(static function (array $row): array {
            return [
                'id' => (int)$row['id'],
                'documentNumber' => $row['document_number'],
                'copyNumber' => $row['copy_number'],
                'copyHolder' => $row['copy_holder'],
                'documentTitle' => $row['document_title'],
                'issuanceDate' => $row['issuance_date'],
                'revisionNumber' => (string)$row['revision_number'],
                'retrievalDate' => $row['retrieval_date'],
                'retrievedRevision' => $row['retrieved_revision'],
                'createdAt' => $row['created_at'],
                'updatedAt' => $row['updated_at'],
            ];
        }, $rows);

        respond(200, [
            'success' => true,
            'data' => $data,
        ]);
    }

    if ($method === 'POST') {
        $input = parseJsonBody();
        $payload = normalizePayload($input);
        $validationMessage = validateRequired($payload);
        if ($validationMessage !== null) {
            respond(422, [
                'success' => false,
                'error' => $validationMessage,
            ]);
        }

        $stmt = $pdo->prepare('INSERT INTO staff_records (document_number, copy_number, copy_holder, document_title, issuance_date, revision_number, retrieval_date, retrieved_revision) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $payload['documentNumber'],
            $payload['copyNumber'],
            $payload['copyHolder'],
            $payload['documentTitle'],
            $payload['issuanceDate'],
            $payload['revisionNumber'],
            $payload['retrievalDate'],
            $payload['retrievedRevision'] === '' ? null : $payload['retrievedRevision'],
        ]);

        respond(201, [
            'success' => true,
            'message' => 'Record created.',
            'id' => (int)$pdo->lastInsertId(),
        ]);
    }

    if ($method === 'PUT') {
        $input = parseJsonBody();
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        if ($id <= 0) {
            respond(422, [
                'success' => false,
                'error' => 'Valid record id is required.',
            ]);
        }

        $payload = normalizePayload($input);
        $validationMessage = validateRequired($payload);
        if ($validationMessage !== null) {
            respond(422, [
                'success' => false,
                'error' => $validationMessage,
            ]);
        }

        $stmt = $pdo->prepare('UPDATE staff_records SET document_number = ?, copy_number = ?, copy_holder = ?, document_title = ?, issuance_date = ?, revision_number = ?, retrieval_date = ?, retrieved_revision = ? WHERE id = ?');
        $stmt->execute([
            $payload['documentNumber'],
            $payload['copyNumber'],
            $payload['copyHolder'],
            $payload['documentTitle'],
            $payload['issuanceDate'],
            $payload['revisionNumber'],
            $payload['retrievalDate'],
            $payload['retrievedRevision'] === '' ? null : $payload['retrievedRevision'],
            $id,
        ]);

        if ($stmt->rowCount() === 0) {
            $checkStmt = $pdo->prepare('SELECT id FROM staff_records WHERE id = ?');
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                respond(404, [
                    'success' => false,
                    'error' => 'Record not found.',
                ]);
            }
        }

        respond(200, [
            'success' => true,
            'message' => 'Record updated.',
        ]);
    }

    if ($method === 'DELETE') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            respond(422, [
                'success' => false,
                'error' => 'Valid record id is required.',
            ]);
        }

        $stmt = $pdo->prepare('DELETE FROM staff_records WHERE id = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            respond(404, [
                'success' => false,
                'error' => 'Record not found.',
            ]);
        }

        respond(200, [
            'success' => true,
            'message' => 'Record deleted.',
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
