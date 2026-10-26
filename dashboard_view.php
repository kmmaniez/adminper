<section class="grid">
    <div class="card metric">Perkara Aktif<strong><?= $totalCases ?></strong></div>
    <div class="card metric">Total Klien<strong><?= $totalClients ?></strong></div>
    <div class="card metric">Pengacara<strong><?= $totalLawyers ?></strong></div>
    <div class="card metric">Pembayaran<strong><?= rupiah($totalPaid) ?></strong></div>
</section>

<section class="table-wrap">
    <h2>Prioritas Perkara Berdasarkan SAW</h2>
    <table>
        <thead>
            <tr>
                <th>Ranking</th>
                <th>No. Perkara</th>
                <th>Klien</th>
                <th>Pengacara</th>
                <th>C1</th>
                <th>C2</th>
                <th>C3</th>
                <th>C4</th>
                <th>C5</th>
                <th>Nilai SAW</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><span class="badge">#<?= $index + 1 ?></span></td>
                    <td><?= e($row['case']['case_number']) ?><br><small><?= e($row['case']['title']) ?></small></td>
                    <td><?= e($row['case']['client_name']) ?></td>
                    <td><?= e($row['case']['lawyer_name'] ?? '-') ?></td>
                    <td><?= $row['scores']['c1'] ?></td>
                    <td><?= $row['scores']['c2'] ?></td>
                    <td><?= $row['scores']['c3'] ?></td>
                    <td><?= $row['scores']['c4'] ?></td>
                    <td><?= $row['scores']['c5'] ?></td>
                    <td><strong><?= number_format($row['saw_value'], 4) ?></strong></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
                <tr><td colspan="10">Belum ada data perkara.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
