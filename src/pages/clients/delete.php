<?php

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT id FROM cases WHERE client_id = ?');
$stmt->execute([$id]);
$caseIds = array_column($stmt->fetchAll(), 'id');

foreach ($caseIds as $caseId) {
    $stmt = db()->prepare('SELECT stored_path FROM case_documents WHERE case_id = ?');
    $stmt->execute([(int) $caseId]);
    foreach ($stmt->fetchAll() as $document) {
        delete_uploaded_file($document['stored_path']);
    }

    $stmt = db()->prepare('SELECT proof_path FROM payments WHERE case_id = ? AND proof_path IS NOT NULL');
    $stmt->execute([(int) $caseId]);
    foreach ($stmt->fetchAll() as $payment) {
        delete_uploaded_file($payment['proof_path']);
    }
}

$stmt = db()->prepare('DELETE FROM clients WHERE id = ?');
$stmt->execute([$id]);

$_SESSION['flash'] = 'Data klien berhasil dihapus.';
redirect('clients');
