<?php

$id = (int) ($_GET['id'] ?? 0);
$payment = find_by_id('payments', $id);

if ($payment) {
    delete_uploaded_file($payment['proof_path']);
    $stmt = db()->prepare('DELETE FROM payments WHERE id = ?');
    $stmt->execute([$id]);
}

$_SESSION['flash'] = 'Data pembayaran berhasil dihapus.';
redirect('payments');
