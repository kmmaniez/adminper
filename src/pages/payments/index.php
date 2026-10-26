<?php

$title = 'Pembayaran';
$q = query_text();
$showForm = false;
$sql = 'SELECT payments.*, cases.case_number, cases.title, clients.name AS client_name
        FROM payments
        JOIN cases ON cases.id = payments.case_id
        JOIN clients ON clients.id = cases.client_id';
$params = [];

if ($q !== '') {
    $sql .= ' WHERE cases.case_number LIKE ? OR cases.title LIKE ? OR clients.name LIKE ? OR payments.method LIKE ? OR payments.notes LIKE ?';
    $params = [like_text($q), like_text($q), like_text($q), like_text($q), like_text($q)];
}

$sql .= ' ORDER BY payments.payment_date DESC, payments.id DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$payments = $stmt->fetchAll();

render('payments/view', compact('title', 'payments', 'q', 'showForm'));
