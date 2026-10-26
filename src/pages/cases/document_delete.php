<?php

$id = (int) ($_GET['id'] ?? 0);
$caseId = (int) ($_GET['case_id'] ?? 0);
$document = find_by_id('case_documents', $id);

if ($document) {
    delete_uploaded_file($document['stored_path']);
    $stmt = db()->prepare('DELETE FROM case_documents WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['flash'] = 'Dokumen perkara berhasil dihapus.';
}

header('Location: index.php?action=case_form&id=' . $caseId);
exit;
