<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../src/bootstrap.php';

$action = $_GET['action'] ?? (is_logged_in() ? 'dashboard' : 'login');

if (!is_logged_in() && !in_array($action, ['login', 'authenticate'], true)) {
    redirect('login');
}

if ($action === 'logout') {
    session_destroy();
    redirect('login');
}

$routes = [
    'login' => 'auth/login.php',
    'authenticate' => 'auth/authenticate.php',
    'dashboard' => 'dashboard.php',
    'lawyers' => 'lawyers/index.php',
    'lawyer_form' => 'lawyers/form.php',
    'lawyer_save' => 'lawyers/save.php',
    'lawyer_delete' => 'lawyers/delete.php',
    'profile' => 'profile.php',
    'profile_save' => 'profile_save.php',
    'clients' => 'clients/index.php',
    'client_form' => 'clients/form.php',
    'client_save' => 'clients/save.php',
    'client_delete' => 'clients/delete.php',
    'categories' => 'categories/index.php',
    'category_form' => 'categories/form.php',
    'category_save' => 'categories/save.php',
    'category_delete' => 'categories/delete.php',
    'cases' => 'cases/index.php',
    'case_form' => 'cases/form.php',
    'case_save' => 'cases/save.php',
    'case_delete' => 'cases/delete.php',
    'case_documents' => 'cases/documents.php',
    'case_document_delete' => 'cases/document_delete.php',
    'reports' => 'reports.php',
    'payments' => 'payments/index.php',
    'payment_form' => 'payments/form.php',
    'payment_save' => 'payments/save.php',
    'payment_delete' => 'payments/delete.php',
    'totals' => 'totals.php',
];

if (!isset($routes[$action])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}

require __DIR__ . '/../src/pages/' . $routes[$action];
