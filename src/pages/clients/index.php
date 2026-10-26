<?php

$title = 'Kelola Data Klien';
$q = query_text();
$showForm = false;

if ($q !== '') {
    $stmt = db()->prepare('SELECT * FROM clients WHERE name LIKE ? OR nik LIKE ? OR phone LIKE ? OR address LIKE ? ORDER BY name');
    $stmt->execute([like_text($q), like_text($q), like_text($q), like_text($q)]);
    $clients = $stmt->fetchAll();
} else {
    $clients = db()->query('SELECT * FROM clients ORDER BY name')->fetchAll();
}

render('clients/view', compact('title', 'clients', 'q', 'showForm'));
