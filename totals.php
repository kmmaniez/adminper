<?php

$title = 'Total Bayar';
$rows = db()->query(
    'SELECT cases.*, clients.name AS client_name, users.name AS lawyer_name, case_categories.name AS category_name,
            COALESCE(payment_totals.paid_total, 0) AS paid_total
     FROM cases
     JOIN clients ON clients.id = cases.client_id
     LEFT JOIN users ON users.id = cases.lawyer_id
     JOIN case_categories ON case_categories.id = cases.category_id
     LEFT JOIN (
        SELECT case_id, SUM(amount) AS paid_total
        FROM payments
        GROUP BY case_id
     ) payment_totals ON payment_totals.case_id = cases.id
     ORDER BY cases.created_at DESC'
)->fetchAll();

render('totals_view', compact('title', 'rows'));
