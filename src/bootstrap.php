<?php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = require __DIR__ . '/../config/database.php';
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['database'],
        $config['charset']
    );

    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function redirect(string $action): void
{
    header('Location: index.php?action=' . $action);
    exit;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function current_user(): array
{
    return $_SESSION['user'] ?? [];
}

function is_admin(): bool
{
    return (current_user()['role'] ?? '') === 'admin';
}

function require_admin(): void
{
    if (!is_admin()) {
        http_response_code(403);
        exit('Akses hanya untuk admin.');
    }
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah(float $value): string
{
    return 'Rp ' . number_format($value, 0, ',', '.');
}

function post(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}

function find_by_id(string $table, int $id): ?array
{
    $allowed = ['users', 'clients', 'case_categories', 'cases', 'payments', 'case_documents'];
    if (!in_array($table, $allowed, true)) {
        return null;
    }

    $stmt = db()->prepare("SELECT * FROM {$table} WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function upload_file(array $file, string $folder): ?array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload file gagal.');
    }

    $maxSize = 5 * 1024 * 1024;
    if ((int) $file['size'] > $maxSize) {
        throw new RuntimeException('Ukuran file maksimal 5 MB.');
    }

    $allowed = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('File harus berupa PDF, JPG, PNG, WEBP, DOC, atau DOCX.');
    }

    $uploadRoot = dirname(__DIR__) . '/public/uploads/' . trim($folder, '/');
    if (!is_dir($uploadRoot)) {
        mkdir($uploadRoot, 0775, true);
    }

    $storedName = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $destination = $uploadRoot . '/' . $storedName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('File tidak dapat disimpan.');
    }

    return [
        'original_name' => $file['name'],
        'stored_path' => 'uploads/' . trim($folder, '/') . '/' . $storedName,
        'mime_type' => $mime,
    ];
}

function upload_multiple_files(string $field, string $folder): array
{
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]['name'])) {
        return [];
    }

    $uploads = [];
    $total = count($_FILES[$field]['name']);

    for ($i = 0; $i < $total; $i++) {
        $file = [
            'name' => $_FILES[$field]['name'][$i],
            'type' => $_FILES[$field]['type'][$i],
            'tmp_name' => $_FILES[$field]['tmp_name'][$i],
            'error' => $_FILES[$field]['error'][$i],
            'size' => $_FILES[$field]['size'][$i],
        ];

        $uploaded = upload_file($file, $folder);
        if ($uploaded) {
            $uploads[] = $uploaded;
        }
    }

    return $uploads;
}

function delete_uploaded_file(?string $path): void
{
    if (!$path) {
        return;
    }

    $root = realpath(dirname(__DIR__) . '/public/uploads');
    $file = realpath(dirname(__DIR__) . '/public/' . ltrim($path, '/'));

    if ($root && $file && strpos($file, $root) === 0 && is_file($file)) {
        unlink($file);
    }
}

function file_link(?string $path): string
{
    return $path ? e($path) : '#';
}

function query_text(string $key = 'q'): string
{
    return trim((string) ($_GET[$key] ?? ''));
}

function like_text(string $value): string
{
    return '%' . $value . '%';
}

function month_name_id(int $month): string
{
    return [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ][$month] ?? '';
}

function date_id(?string $date): string
{
    if (!$date) {
        return '-';
    }

    $value = new DateTimeImmutable($date);
    return $value->format('d') . ' ' . month_name_id((int) $value->format('n')) . ' ' . $value->format('Y');
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/partials/header.php';
    require __DIR__ . '/pages/' . $view . '.php';
    require __DIR__ . '/partials/footer.php';
}

function payment_total(int $caseId): float
{
    $stmt = db()->prepare('SELECT COALESCE(SUM(amount), 0) FROM payments WHERE case_id = ?');
    $stmt->execute([$caseId]);
    return (float) $stmt->fetchColumn();
}

function payment_status_score(float $totalFee, float $paid): int
{
    if ($totalFee <= 0) {
        return 1;
    }

    $percent = ($paid / $totalFee) * 100;

    if ($percent >= 100) {
        return 5;
    }
    if ($percent > 75) {
        return 4;
    }
    if ($percent >= 50) {
        return 3;
    }
    if ($percent > 0) {
        return 2;
    }
    return 1;
}

function payment_status_label(float $totalFee, float $paid): string
{
    $score = payment_status_score($totalFee, $paid);
    return [
        5 => 'lunas',
        4 => 'DP > 75%',
        3 => 'DP 50%',
        2 => 'DP <50%',
        1 => 'belum membayar',
    ][$score];
}

function days_until_hearing(?string $date): ?int
{
    if (!$date) {
        return null;
    }

    $today = new DateTimeImmutable('today');
    $hearing = new DateTimeImmutable($date);
    return (int) $today->diff($hearing)->format('%r%a');
}

function remaining_time_score(?string $date): int
{
    $days = days_until_hearing($date);

    if ($days === null) {
        return 5;
    }
    if ($days <= 3) {
        return 1;
    }
    if ($days <= 7) {
        return 2;
    }
    if ($days <= 14) {
        return 3;
    }
    return 4;
}

function document_score(string $status): int
{
    return [
        'sangat lengkap' => 5,
        'lengkap' => 4,
        'cukup lengkap' => 3,
        'kurang lengkap' => 2,
        'tidak lengkap' => 1,
    ][$status] ?? 1;
}

function administration_score(string $status): int
{
    return [
        'belum diproses' => 1,
        'berkas sudah didaftarkan' => 2,
        'menunggu jadwal sidang' => 3,
        'sidang sedang berlangsung' => 3,
        'perkara selesai' => 5,
    ][$status] ?? 1;
}

function saw_rows(): array
{
    $sql = 'SELECT cases.*, clients.name AS client_name, users.name AS lawyer_name, case_categories.name AS category_name, case_categories.saw_score AS category_score
            FROM cases
            JOIN clients ON clients.id = cases.client_id
            LEFT JOIN users ON users.id = cases.lawyer_id
            JOIN case_categories ON case_categories.id = cases.category_id
            WHERE cases.administration_status <> "perkara selesai"
            ORDER BY cases.created_at DESC';
    $cases = db()->query($sql)->fetchAll();

    if (!$cases) {
        return [];
    }

    $matrix = [];
    foreach ($cases as $case) {
        $paid = payment_total((int) $case['id']);
        $matrix[] = [
            'case' => $case,
            'paid' => $paid,
            'scores' => [
                'c1' => remaining_time_score($case['hearing_date']),
                'c2' => (int) $case['category_score'],
                'c3' => document_score($case['document_status']),
                'c4' => administration_score($case['administration_status']),
                'c5' => payment_status_score((float) $case['total_fee'], $paid),
            ],
        ];
    }

    $mins = [
        'c1' => min(array_column(array_column($matrix, 'scores'), 'c1')),
        'c4' => min(array_column(array_column($matrix, 'scores'), 'c4')),
    ];
    $maxes = [
        'c2' => max(array_column(array_column($matrix, 'scores'), 'c2')),
        'c3' => max(array_column(array_column($matrix, 'scores'), 'c3')),
        'c5' => max(array_column(array_column($matrix, 'scores'), 'c5')),
    ];

    foreach ($matrix as &$row) {
        $s = $row['scores'];
        $normalized = [
            'c1' => $mins['c1'] / max($s['c1'], 1),
            'c2' => $s['c2'] / max($maxes['c2'], 1),
            'c3' => $s['c3'] / max($maxes['c3'], 1),
            'c4' => $mins['c4'] / max($s['c4'], 1),
            'c5' => $s['c5'] / max($maxes['c5'], 1),
        ];
        $row['normalized'] = $normalized;
        $row['saw_value'] = ($normalized['c1'] * 0.25)
            + ($normalized['c2'] * 0.20)
            + ($normalized['c3'] * 0.20)
            + ($normalized['c4'] * 0.15)
            + ($normalized['c5'] * 0.20);
    }
    unset($row);

    usort($matrix, fn ($a, $b) => $b['saw_value'] <=> $a['saw_value']);
    return $matrix;
}
