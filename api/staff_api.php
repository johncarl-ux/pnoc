<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

function respond(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

function readJsonBody(): array
{
    $rawBody = file_get_contents('php://input');
    if (!$rawBody) {
        return [];
    }

    $decoded = json_decode($rawBody, true);
    return is_array($decoded) ? $decoded : [];
}

function toRecordArray(array $row): array
{
    return [
        'id' => (int) $row['id'],
        'documentNumber' => $row['document_number'] ?? '',
        'copyNumber' => $row['copy_number'] ?? '',
        'copyHolder' => $row['copy_holder'] ?? '',
        'documentTitle' => $row['document_title'] ?? '',
        'issuanceDate' => $row['issuance_date'] ?? '',
        'revisionNumber' => (string) ($row['revision_number'] ?? '0'),
        'retrievalDate' => $row['retrieval_date'] ?? '',
        'retrievedRevision' => $row['retrieved_revision'] ?? ''
    ];
}

function validateRecordPayload(array $payload): array
{
    $record = [
        'documentNumber' => trim((string) ($payload['documentNumber'] ?? '')),
        'copyNumber' => trim((string) ($payload['copyNumber'] ?? '')),
        'copyHolder' => trim((string) ($payload['copyHolder'] ?? '')),
        'documentTitle' => trim((string) ($payload['documentTitle'] ?? '')),
        'issuanceDate' => trim((string) ($payload['issuanceDate'] ?? '')),
        'revisionNumber' => trim((string) ($payload['revisionNumber'] ?? '0')),
        'retrievalDate' => trim((string) ($payload['retrievalDate'] ?? '')),
        'retrievedRevision' => trim((string) ($payload['retrievedRevision'] ?? ''))
    ];

    if (
        $record['documentNumber'] === '' ||
        $record['copyNumber'] === '' ||
        $record['copyHolder'] === '' ||
        $record['documentTitle'] === ''
    ) {
        respond(422, [
            'success' => false,
            'message' => "Please complete Document Number, Copy Number, Copy Holder's Name, and Document Title."
        ]);
    }

    return $record;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $db = getDbConnection();

    if ($method === 'GET') {
        $result = $db->query('SELECT id, document_number, copy_number, copy_holder, document_title, issuance_date, revision_number, retrieval_date, retrieved_revision FROM staff_documents ORDER BY id DESC');

        $records = [];
        while ($row = $result->fetch_assoc()) {
            $records[] = toRecordArray($row);
        }

        respond(200, ['success' => true, 'data' => $records]);
    }

    if ($method === 'POST') {
        $data = readJsonBody();
        $record = validateRecordPayload($data);

        $statement = $db->prepare('INSERT INTO staff_documents (document_number, copy_number, copy_holder, document_title, issuance_date, revision_number, retrieval_date, retrieved_revision) VALUES (?, ?, ?, ?, NULLIF(?, ""), ?, NULLIF(?, ""), NULLIF(?, ""))');
        $revisionNumber = (int) $record['revisionNumber'];
        $statement->bind_param(
            'sssssiss',
            $record['documentNumber'],
            $record['copyNumber'],
            $record['copyHolder'],
            $record['documentTitle'],
            $record['issuanceDate'],
            $revisionNumber,
            $record['retrievalDate'],
            $record['retrievedRevision']
        );
        $statement->execute();

        respond(201, ['success' => true, 'id' => $db->insert_id]);
    }

    if ($method === 'PUT') {
        $data = readJsonBody();
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) {
            respond(422, ['success' => false, 'message' => 'Invalid record id.']);
        }

        $record = validateRecordPayload($data);

        $statement = $db->prepare('UPDATE staff_documents SET document_number = ?, copy_number = ?, copy_holder = ?, document_title = ?, issuance_date = NULLIF(?, ""), revision_number = ?, retrieval_date = NULLIF(?, ""), retrieved_revision = NULLIF(?, "") WHERE id = ?');
        $revisionNumber = (int) $record['revisionNumber'];
        $statement->bind_param(
            'sssssissi',
            $record['documentNumber'],
            $record['copyNumber'],
            $record['copyHolder'],
            $record['documentTitle'],
            $record['issuanceDate'],
            $revisionNumber,
            $record['retrievalDate'],
            $record['retrievedRevision'],
            $id
        );
        $statement->execute();

        respond(200, ['success' => true]);
    }

    if ($method === 'DELETE') {
        $data = readJsonBody();
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) {
            respond(422, ['success' => false, 'message' => 'Invalid record id.']);
        }

        $statement = $db->prepare('DELETE FROM staff_documents WHERE id = ?');
        $statement->bind_param('i', $id);
        $statement->execute();

        respond(200, ['success' => true]);
    }

    respond(405, ['success' => false, 'message' => 'Method not allowed.']);
} catch (mysqli_sql_exception $exception) {
    $errorCode = (int) $exception->getCode();

    if ($errorCode === 1062) {
        respond(409, [
            'success' => false,
            'message' => 'This Document Number and Copy Number already exists.'
        ]);
    }

    respond(500, [
        'success' => false,
        'message' => 'Database error: ' . $exception->getMessage()
    ]);
} catch (Throwable $exception) {
    respond(500, [
        'success' => false,
        'message' => 'Server error: ' . $exception->getMessage()
    ]);
}
