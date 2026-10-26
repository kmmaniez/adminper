<?php

$title = isset($_GET['id']) ? 'Edit Kategori Perkara' : 'Tambah Kategori Perkara';
$edit = isset($_GET['id']) ? find_by_id('case_categories', (int) $_GET['id']) : null;
$showForm = true;

render('categories/view', compact('title', 'edit', 'showForm'));
