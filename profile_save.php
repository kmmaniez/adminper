<?php

$id = (int) current_user()['id'];
$password = (string) post('password', '');

if ($password !== '') {
    $stmt = db()->prepare('UPDATE users SET name=?, email=?, password=?, phone=?, address=?, specialization=? WHERE id=?');
    $stmt->execute([post('name'), post('email'), password_hash($password, PASSWORD_DEFAULT), post('phone'), post('address'), post('specialization'), $id]);
} else {
    $stmt = db()->prepare('UPDATE users SET name=?, email=?, phone=?, address=?, specialization=? WHERE id=?');
    $stmt->execute([post('name'), post('email'), post('phone'), post('address'), post('specialization'), $id]);
}

$_SESSION['user']['name'] = (string) post('name');
$_SESSION['user']['email'] = (string) post('email');
$_SESSION['flash'] = 'Data pribadi berhasil diperbarui.';
redirect('profile');
