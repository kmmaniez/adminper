<?php

require_admin();

$title = 'Kelola Data Pengacara';
$q = query_text();
$showForm = false;

if ($q !== '') {
    $stmt = db()->prepare("SELECT * FROM users WHERE role = 'pengacara' AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR specialization LIKE ?) ORDER BY name");
    $stmt->execute([like_text($q), like_text($q), like_text($q), like_text($q)]);
    $lawyers = $stmt->fetchAll();
} else {
    $lawyers = db()->query("SELECT * FROM users WHERE role = 'pengacara' ORDER BY name")->fetchAll();
}

render('lawyers/view', compact('title', 'lawyers', 'q', 'showForm'));
