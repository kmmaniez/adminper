<?php $user = current_user(); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kantor Hukum Rotama, S.H & Rekan</title>
    <link rel="stylesheet" href="assets/styles.css?v=2">
</head>
<body>
<aside class="sidebar">
    <div class="brand">
        <img class="brand-logo" src="assets/logo.jpeg" alt="Logo Kantor">
        <div>
            <strong>Rotama, S.H & Rekan</strong>
            <small>Administrasi Perkara</small>
        </div>
    </div>
    <nav>
        <a href="index.php?action=dashboard">Dashboard</a>
        <?php if (is_admin()): ?>
            <a href="index.php?action=lawyers">Data Pengacara</a>
            <a href="index.php?action=categories">Kategori Perkara</a>
        <?php endif; ?>
        <a href="index.php?action=profile">Data Pribadi</a>
        <a href="index.php?action=clients">Data Klien</a>
        <a href="index.php?action=cases">Data Perkara</a>
        <a href="index.php?action=reports">Rekap Perkara</a>
        <a href="index.php?action=payments">Pembayaran</a>
        <a href="index.php?action=totals">Total Bayar</a>
    </nav>
</aside>
<main class="main">
    <header class="topbar">
        <div>
            <span class="eyebrow">Kantor Hukum</span>
            <h1><?= e($title ?? 'Administrasi Perkara') ?></h1>
        </div>
        <div class="userbox">
            <span><?= e($user['name'] ?? '') ?></span>
            <small><?= e($user['role'] ?? '') ?></small>
            <a class="btn ghost" href="index.php?action=logout">Keluar</a>
        </div>
    </header>
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
