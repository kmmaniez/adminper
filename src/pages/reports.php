<?php

$title = 'Rekap Perkara';
$month = (int) ($_GET['month'] ?? date('n'));
$year = (int) ($_GET['year'] ?? date('Y'));
$status = $_GET['status'] ?? '';
$category = (int) ($_GET['category_id'] ?? 0);
$categories = db()->query('SELECT id, name FROM case_categories ORDER BY name')->fetchAll();

$where = ['MONTH(cases.created_at) = ?', 'YEAR(cases.created_at) = ?'];
$params = [$month, $year];

if ($status !== '') {
    $where[] = 'cases.administration_status = ?';
    $params[] = $status;
}

if ($category > 0) {
    $where[] = 'cases.category_id = ?';
    $params[] = $category;
}

$sql = 'SELECT cases.*, clients.name AS client_name, users.name AS lawyer_name, case_categories.name AS category_name,
               COALESCE(payment_totals.paid_total, 0) AS paid_total
        FROM cases
        JOIN clients ON clients.id = cases.client_id
        LEFT JOIN users ON users.id = cases.lawyer_id
        JOIN case_categories ON case_categories.id = cases.category_id
        LEFT JOIN (
            SELECT case_id, SUM(amount) AS paid_total
            FROM payments
            GROUP BY case_id
        ) payment_totals ON payment_totals.case_id = cases.id';

if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY cases.created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$summary = [
    'total' => count($rows),
    'categories' => [],
];

foreach ($categories as $item) {
    $summary['categories'][$item['name']] = 0;
}

foreach ($rows as $row) {
    $summary['categories'][$row['category_name']] = ($summary['categories'][$row['category_name']] ?? 0) + 1;
}

render('reports_view', compact('title', 'rows', 'categories', 'status', 'category', 'month', 'year', 'summary'));
