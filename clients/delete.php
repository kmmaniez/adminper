<?php

$stmt = db()->prepare('DELETE FROM clients WHERE id = ?');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);

$_SESSION['flash'] = 'Data klien berhasil dihapus.';
redirect('clients');
