<?php

$title = 'Kategori Perkara';
$edit = isset($_GET['id']) ? find_by_id('case_categories', (int) $_GET['id']) : null;
$categories = db()->query('SELECT * FROM case_categories ORDER BY saw_score DESC, name')->fetchAll();

render('categories/view', compact('title', 'edit', 'categories'));
