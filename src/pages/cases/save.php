<?php

$id = (int) post('id', 0);
$lawyerId = post('lawyer_id') !== '' ? (int) post('lawyer_id') : null;
$hearingDate = post('hearing_date') !== '' ? post('hearing_date') : null;

$payload = [
    post('case_number'),
    post('title'),
    (int) post('client_id'),
    $lawyerId,
    (int) post('category_id'),
    $hearingDate,
    post('document_status'),
    post('administration_status'),
    (float) post('total_fee'),
    post('notes'),
];

try {
    if ($id > 0) {
        $stmt = db()->prepare('UPDATE cases SET case_number=?, title=?, client_id=?, lawyer_id=?, category_id=?, hearing_date=?, document_status=?, administration_status=?, total_fee=?, notes=? WHERE id=?');
        $stmt->execute([...$payload, $id]);
        $caseId = $id;
    } else {
        $stmt = db()->prepare('INSERT INTO cases (case_number, title, client_id, lawyer_id, category_id, hearing_date, document_status, administration_status, total_fee, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute($payload);
        $caseId = (int) db()->lastInsertId();
    }

    foreach (upload_multiple_files('documents', 'case-documents') as $document) {
        $stmt = db()->prepare('INSERT INTO case_documents (case_id, original_name, stored_path, mime_type) VALUES (?, ?, ?, ?)');
        $stmt->execute([$caseId, $document['original_name'], $document['stored_path'], $document['mime_type']]);
    }

    $_SESSION['flash'] = 'Data perkara berhasil disimpan.';
} catch (RuntimeException $exception) {
    $_SESSION['flash'] = $exception->getMessage();
}

redirect('cases');
