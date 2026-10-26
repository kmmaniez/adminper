<?php

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT stored_path FROM case_documents WHERE case_id = ?');
$stmt->execute([$id]);
foreach ($stmt->fetchAll() as $document) {
    delete_uploaded_file($document['stored_path']);
}

$stmt = db()->prepare('SELECT proof_path FROM payments WHERE case_id = ? AND proof_path IS NOT NULL');
$stmt->execute([$id]);
foreach ($stmt->fetchAll() as $payment) {
    delete_uploaded_file($payment['proof_path']);
}

$stmt = db()->prepare('DELETE FROM cases WHERE id = ?');
$stmt->execute([$id]);

$_SESSION['flash'] = 'Data perkara berhasil dihapus.';
redirect('cases');
