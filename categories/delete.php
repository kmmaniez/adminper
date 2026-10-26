<?php

try {
    $stmt = db()->prepare('DELETE FROM case_categories WHERE id = ?');
    $stmt->execute([(int) ($_GET['id'] ?? 0)]);
    $_SESSION['flash'] = 'Kategori perkara berhasil dihapus.';
} catch (PDOException $exception) {
    $_SESSION['flash'] = 'Kategori tidak dapat dihapus karena masih dipakai oleh data perkara.';
}

redirect('categories');
