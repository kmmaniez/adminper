<?php

$stmt = db()->prepare('DELETE FROM payments WHERE id = ?');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);

$_SESSION['flash'] = 'Data pembayaran berhasil dihapus.';
redirect('payments');
