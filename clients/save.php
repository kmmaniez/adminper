<?php

$id = (int) post('id', 0);

if ($id > 0) {
    $stmt = db()->prepare('UPDATE clients SET name=?, nik=?, phone=?, address=? WHERE id=?');
    $stmt->execute([post('name'), post('nik'), post('phone'), post('address'), $id]);
} else {
    $stmt = db()->prepare('INSERT INTO clients (name, nik, phone, address) VALUES (?, ?, ?, ?)');
    $stmt->execute([post('name'), post('nik'), post('phone'), post('address')]);
}

$_SESSION['flash'] = 'Data klien berhasil disimpan.';
redirect('clients');
