<?php

$title = 'Kelola Data Perkara';
$edit = isset($_GET['id']) ? find_by_id('cases', (int) $_GET['id']) : null;
$clients = db()->query('SELECT id, name FROM clients ORDER BY name')->fetchAll();
$lawyers = db()->query("SELECT id, name FROM users WHERE role = 'pengacara' ORDER BY name")->fetchAll();
$categories = db()->query('SELECT id, name FROM case_categories ORDER BY name')->fetchAll();
$cases = db()->query(
    'SELECT cases.*, clients.name AS client_name, users.name AS lawyer_name, case_categories.name AS category_name
     FROM cases
     JOIN clients ON clients.id = cases.client_id
     LEFT JOIN users ON users.id = cases.lawyer_id
     JOIN case_categories ON case_categories.id = cases.category_id
     ORDER BY cases.created_at DESC'
)->fetchAll();

render('cases/view', compact('title', 'edit', 'clients', 'lawyers', 'categories', 'cases'));
