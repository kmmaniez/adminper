<?php

$stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$stmt->execute([post('email')]);
$user = $stmt->fetch();

if (!$user || !password_verify((string) post('password'), $user['password'])) {
    $_SESSION['flash'] = 'Email atau password tidak sesuai.';
    redirect('login');
}

session_regenerate_id(true);

$_SESSION['user'] = [
    'id' => $user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'role' => $user['role'],
];

redirect('dashboard');
