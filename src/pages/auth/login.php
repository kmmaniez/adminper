<?php if (is_logged_in()) redirect('dashboard'); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Kantor Hukum Rotama, S.H & Rekan</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="login-page">
    <section class="login-panel">
        <div class="brand large">
            <img class="brand-logo" src="assets/logo.jpeg" alt="Logo Kantor">
            <div>
                <strong>Kantor Hukum Rotama, S.H & Rekan</strong>
                <small>Administrasi Perkara</small>
            </div>
        </div>
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
        <?php endif; ?>
        <form method="post" action="index.php?action=authenticate" class="form stack">
            <label>Email
                <input type="email" name="email" required autofocus>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <button class="btn primary" type="submit">Masuk</button>

    </section>
</body>
</html>
