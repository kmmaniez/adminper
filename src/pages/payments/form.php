<?php

$title = isset($_GET['id']) ? 'Edit Pembayaran' : 'Tambah Pembayaran';
$edit = isset($_GET['id']) ? find_by_id('payments', (int) $_GET['id']) : null;
$cases = db()->query(
    'SELECT cases.id, cases.case_number, cases.title, clients.name AS client_name
     FROM cases
     JOIN clients ON clients.id = cases.client_id
     ORDER BY cases.created_at DESC'
)->fetchAll();
$showForm = true;

render('payments/view', compact('title', 'edit', 'cases', 'showForm'));
