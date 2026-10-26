<?php

$title = 'Pembayaran';
$edit = isset($_GET['id']) ? find_by_id('payments', (int) $_GET['id']) : null;
$cases = db()->query(
    'SELECT cases.id, cases.case_number, cases.title, clients.name AS client_name
     FROM cases
     JOIN clients ON clients.id = cases.client_id
     ORDER BY cases.created_at DESC'
)->fetchAll();
$payments = db()->query(
    'SELECT payments.*, cases.case_number, cases.title, clients.name AS client_name
     FROM payments
     JOIN cases ON cases.id = payments.case_id
     JOIN clients ON clients.id = cases.client_id
     ORDER BY payments.payment_date DESC, payments.id DESC'
)->fetchAll();

render('payments/view', compact('title', 'edit', 'cases', 'payments'));
