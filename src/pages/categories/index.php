<?php

$title = 'Kategori Perkara';
$q = query_text();
$showForm = false;

if ($q !== '') {
    $stmt = db()->prepare('SELECT * FROM case_categories WHERE name LIKE ? OR saw_score LIKE ? ORDER BY saw_score DESC, name');
    $stmt->execute([like_text($q), like_text($q)]);
    $categories = $stmt->fetchAll();
} else {
    $categories = db()->query('SELECT * FROM case_categories ORDER BY saw_score DESC, name')->fetchAll();
}

render('categories/view', compact('title', 'categories', 'q', 'showForm'));
