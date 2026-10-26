<?php

$title = 'Dashboard SAW';
$rows = saw_rows();
$totalCases = count($rows);
$totalClients = (int) db()->query('SELECT COUNT(*) FROM clients')->fetchColumn();
$totalLawyers = (int) db()->query("SELECT COUNT(*) FROM users WHERE role = 'pengacara'")->fetchColumn();
$totalPaid = (float) db()->query('SELECT COALESCE(SUM(amount), 0) FROM payments')->fetchColumn();

render('dashboard_view', compact('title', 'rows', 'totalCases', 'totalClients', 'totalLawyers', 'totalPaid'));
