<?php

$id = (int) post('id', 0);
$payload = [
    (int) post('case_id'),
    post('payment_date'),
    (float) post('amount'),
    post('method'),
    post('notes'),
];

try {
    $proof = upload_file($_FILES['proof'] ?? [], 'payment-proofs');

    if ($id > 0) {
        if ($proof) {
            $old = find_by_id('payments', $id);
            delete_uploaded_file($old['proof_path'] ?? null);
            $stmt = db()->prepare('UPDATE payments SET case_id=?, payment_date=?, amount=?, method=?, notes=?, proof_original_name=?, proof_path=?, proof_mime_type=? WHERE id=?');
            $stmt->execute([...$payload, $proof['original_name'], $proof['stored_path'], $proof['mime_type'], $id]);
        } else {
            $stmt = db()->prepare('UPDATE payments SET case_id=?, payment_date=?, amount=?, method=?, notes=? WHERE id=?');
            $stmt->execute([...$payload, $id]);
        }
    } else {
        $stmt = db()->prepare('INSERT INTO payments (case_id, payment_date, amount, method, notes, proof_original_name, proof_path, proof_mime_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            ...$payload,
            $proof['original_name'] ?? null,
            $proof['stored_path'] ?? null,
            $proof['mime_type'] ?? null,
        ]);
    }

    $_SESSION['flash'] = 'Data pembayaran berhasil disimpan.';
} catch (RuntimeException $exception) {
    $_SESSION['flash'] = $exception->getMessage();
}

redirect('payments');
