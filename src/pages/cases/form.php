<?php

$title = isset($_GET['id']) ? 'Edit Perkara' : 'Tambah Perkara';
$edit = isset($_GET['id']) ? find_by_id('cases', (int) $_GET['id']) : null;
$clients = db()->query('SELECT id, name FROM clients ORDER BY name')->fetchAll();
$lawyers = db()->query("SELECT id, name FROM users WHERE role = 'pengacara' ORDER BY name")->fetchAll();
$categories = db()->query('SELECT id, name FROM case_categories ORDER BY name')->fetchAll();
$caseDocuments = [];
$showForm = true;

if ($edit) {
    $stmt = db()->prepare('SELECT * FROM case_documents WHERE case_id = ? ORDER BY created_at DESC');
    $stmt->execute([(int) $edit['id']]);
    $caseDocuments = $stmt->fetchAll();
}

render('cases/view', compact('title', 'edit', 'clients', 'lawyers', 'categories', 'caseDocuments', 'showForm'));
