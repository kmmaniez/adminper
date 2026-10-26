<?php

$title = isset($_GET['id']) ? 'Edit Klien' : 'Tambah Klien';
$edit = isset($_GET['id']) ? find_by_id('clients', (int) $_GET['id']) : null;
$showForm = true;

render('clients/view', compact('title', 'edit', 'showForm'));
