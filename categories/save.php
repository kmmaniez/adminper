<?php

$id = (int) post('id', 0);

if ($id > 0) {
    $stmt = db()->prepare('UPDATE case_categories SET name=?, saw_score=? WHERE id=?');
    $stmt->execute([post('name'), (int) post('saw_score'), $id]);
} else {
    $stmt = db()->prepare('INSERT INTO case_categories (name, saw_score) VALUES (?, ?)');
    $stmt->execute([post('name'), (int) post('saw_score')]);
}

$_SESSION['flash'] = 'Kategori perkara berhasil disimpan.';
redirect('categories');
