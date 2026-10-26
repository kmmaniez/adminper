<?php

require_admin();

$title = 'Kelola Data Pengacara';
$edit = isset($_GET['id']) ? find_by_id('users', (int) $_GET['id']) : null;
$lawyers = db()->query("SELECT * FROM users WHERE role = 'pengacara' ORDER BY name")->fetchAll();

render('lawyers/view', compact('title', 'edit', 'lawyers'));
