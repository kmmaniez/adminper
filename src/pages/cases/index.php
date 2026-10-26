<?php

$title = 'Kelola Data Perkara';
$q = query_text();
$showForm = false;
$sql = 'SELECT cases.*, clients.name AS client_name, users.name AS lawyer_name, case_categories.name AS category_name
        FROM cases
        JOIN clients ON clients.id = cases.client_id
        LEFT JOIN users ON users.id = cases.lawyer_id
        JOIN case_categories ON case_categories.id = cases.category_id';
$params = [];

if ($q !== '') {
    $sql .= ' WHERE cases.case_number LIKE ? OR cases.title LIKE ? OR clients.name LIKE ? OR users.name LIKE ? OR case_categories.name LIKE ? OR cases.administration_status LIKE ?';
    $params = [like_text($q), like_text($q), like_text($q), like_text($q), like_text($q), like_text($q)];
}

$sql .= ' ORDER BY cases.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$cases = $stmt->fetchAll();
render('cases/view', compact('title', 'cases', 'q', 'showForm'));
