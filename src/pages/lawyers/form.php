<?php

require_admin();

$title = isset($_GET['id']) ? 'Edit Pengacara' : 'Tambah Pengacara';
$edit = isset($_GET['id']) ? find_by_id('users', (int) $_GET['id']) : null;
$showForm = true;

render('lawyers/view', compact('title', 'edit', 'showForm'));
