<?php

$caseId = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare(
    'SELECT cases.*, clients.name AS client_name, users.name AS lawyer_name, case_categories.name AS category_name
     FROM cases
     JOIN clients ON clients.id = cases.client_id
     LEFT JOIN users ON users.id = cases.lawyer_id
     JOIN case_categories ON case_categories.id = cases.category_id
     WHERE cases.id = ?'
);
$stmt->execute([$caseId]);
$case = $stmt->fetch();

if (!$case) {
    http_response_code(404);
    exit('Data perkara tidak ditemukan.');
}

$q = query_text();

if ($q !== '') {
    $stmt = db()->prepare('SELECT * FROM case_documents WHERE case_id = ? AND original_name LIKE ? ORDER BY created_at DESC');
    $stmt->execute([$caseId, like_text($q)]);
} else {
    $stmt = db()->prepare('SELECT * FROM case_documents WHERE case_id = ? ORDER BY created_at DESC');
    $stmt->execute([$caseId]);
}

$documents = $stmt->fetchAll();
$title = 'Dokumen Perkara';

render('cases/documents_view', compact('title', 'case', 'documents', 'q'));
