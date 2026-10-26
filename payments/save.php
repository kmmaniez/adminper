<?php

$id = (int) post('id', 0);
$payload = [
    (int) post('case_id'),
    post('payment_date'),
    (float) post('amount'),
    post('method'),
    post('notes'),
];

if ($id > 0) {
    $stmt = db()->prepare('UPDATE payments SET case_id=?, payment_date=?, amount=?, method=?, notes=? WHERE id=?');
    $stmt->execute([...$payload, $id]);
} else {
    $stmt = db()->prepare('INSERT INTO payments (case_id, payment_date, amount, method, notes) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute($payload);
}

$_SESSION['flash'] = 'Data pembayaran berhasil disimpan.';
redirect('payments');
