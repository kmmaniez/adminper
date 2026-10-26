<?php

require_admin();

$stmt = db()->prepare('DELETE FROM users WHERE id = ? AND role = "pengacara"');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);

$_SESSION['flash'] = 'Data pengacara berhasil dihapus.';
redirect('lawyers');
