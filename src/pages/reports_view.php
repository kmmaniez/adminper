<?php
$administrationStatuses = ['belum diproses', 'berkas sudah didaftarkan', 'menunggu jadwal sidang', 'sidang sedang berlangsung', 'perkara selesai'];
$currentYear = (int) date('Y');
?>
<section class="form-panel no-print">
    <h2>Filter Rekap</h2>
    <form class="form" method="get" action="index.php">
        <input type="hidden" name="action" value="reports">
        <label>Bulan
            <select name="month">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $month === $i ? 'selected' : '' ?>><?= e(month_name_id($i)) ?></option>
                <?php endfor; ?>
            </select>
        </label>
        <label>Tahun
            <select name="year">
                <?php for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++): ?>
                    <option value="<?= $i ?>" <?= $year === $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </label>
        <label>Status Administrasi
            <select name="status">
                <option value="">Semua status</option>
                <?php foreach ($administrationStatuses as $item): ?>
                    <option value="<?= e($item) ?>" <?= $status === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Kategori
            <select name="category_id">
                <option value="0">Semua kategori</option>
                <?php foreach ($categories as $item): ?>
                    <option value="<?= $item['id'] ?>" <?= (int) $category === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <div class="actions span-2">
            <button class="btn primary" type="submit">Tampilkan</button>
            <a class="btn ghost" href="index.php?action=reports">Reset</a>
            <button class="btn warning" type="button" onclick="window.print()">Cetak</button>
        </div>
    </form>
</section>

<section class="report-paper">
    <header class="report-header">
        <img class="report-logo-img" src="assets/logo.jpeg" alt="Logo Kantor">
        <div>
            <strong>KANTOR HUKUM</strong>
            <h2>ROTAMA, S.H & REKAN</h2>
            <p>Administrasi Perkara</p>
        </div>
    </header>

    <div class="report-divider"></div>

    <h2 class="report-title">Laporan Perkara Bulan <?= e(month_name_id($month)) ?> <?= $year ?></h2>

    <div class="report-summary">
        <p>Jumlah Total Perkara: <strong><?= (int) $summary['total'] ?></strong></p>
        <?php foreach ($summary['categories'] as $name => $count): ?>
            <?php if ($count > 0): ?>
                <p>Jumlah Perkara <?= e(ucwords($name)) ?>: <strong><?= (int) $count ?></strong></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>No Perkara</th>
                <th>Jenis Perkara</th>
                <th>Nama Perkara</th>
                <th>Klien</th>
                <th>Pengacara</th>
                <th>Status</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <?php
                $isFinished = $row['administration_status'] === 'perkara selesai';
                $displayStatus = $isFinished ? 'Selesai' : 'Berjalan';
                ?>
                <tr>
                    <td><?= e($row['case_number']) ?></td>
                    <td><?= e(ucwords($row['category_name'])) ?></td>
                    <td><?= e($row['title']) ?></td>
                    <td><?= e($row['client_name']) ?></td>
                    <td><?= e($row['lawyer_name'] ?? '-') ?></td>
                    <td><?= e($displayStatus) ?></td>
                    <td><?= e(date_id($row['created_at'])) ?></td>
                    <td><?= e($isFinished ? date_id($row['hearing_date']) : '-') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
                <tr><td colspan="8">Tidak ada data perkara pada periode ini.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
