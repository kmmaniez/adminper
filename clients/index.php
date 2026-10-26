<?php

$title = 'Kelola Data Klien';
$edit = isset($_GET['id']) ? find_by_id('clients', (int) $_GET['id']) : null;
$clients = db()->query('SELECT * FROM clients ORDER BY name')->fetchAll();

render('clients/view', compact('title', 'edit', 'clients'));
