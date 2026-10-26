<?php

require_admin();

$id = (int) post('id', 0);
$password = (string) post('password', '');

if ($id > 0) {
    if ($password !== '') {
        $stmt = db()->prepare('UPDATE users SET name=?, email=?, password=?, phone=?, address=?, specialization=? WHERE id=? AND role="pengacara"');
        $stmt->execute([post('name'), post('email'), password_hash($password, PASSWORD_DEFAULT), post('phone'), post('address'), post('specialization'), $id]);
    } else {
        $stmt = db()->prepare('UPDATE users SET name=?, email=?, phone=?, address=?, specialization=? WHERE id=? AND role="pengacara"');
        $stmt->execute([post('name'), post('email'), post('phone'), post('address'), post('specialization'), $id]);
    }
} else {
    $stmt = db()->prepare('INSERT INTO users (name, email, password, role, phone, address, specialization) VALUES (?, ?, ?, "pengacara", ?, ?, ?)');
    $stmt->execute([post('name'), post('email'), password_hash($password, PASSWORD_DEFAULT), post('phone'), post('address'), post('specialization')]);
}

$_SESSION['flash'] = 'Data pengacara berhasil disimpan.';
redirect('lawyers');
