<?php

$stmt = db()->prepare('DELETE FROM cases WHERE id = ?');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);

$_SESSION['flash'] = 'Data perkara berhasil dihapus.';
redirect('cases');
