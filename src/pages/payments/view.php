<?php if ($showForm ?? false): ?>
<section class="form-panel">
    <h2><?= $edit ? 'Edit Pembayaran' : 'Tambah Pembayaran' ?></h2>
    <form class="form" method="post" action="index.php?action=payment_save" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>Perkara
            <select name="case_id" required>
                <option value="">Pilih perkara</option>
                <?php foreach ($cases as $case): ?>
                    <option value="<?= $case['id'] ?>" <?= (int) ($edit['case_id'] ?? 0) === (int) $case['id'] ? 'selected' : '' ?>>
                        <?= e($case['case_number'] . ' - ' . $case['client_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Tanggal Bayar
            <input type="date" name="payment_date" required value="<?= e($edit['payment_date'] ?? date('Y-m-d')) ?>">
        </label>
        <label>Jumlah Bayar
            <input type="number" name="amount" min="0" step="1000" required value="<?= e((string) ($edit['amount'] ?? 0)) ?>">
        </label>
        <label>Metode
            <input name="method" required value="<?= e($edit['method'] ?? 'Transfer') ?>">
        </label>
        <label class="span-2">Bukti Pembayaran
            <input type="file" name="proof" accept=".pdf,.doc,.docx,image/jpeg,image/png,image/webp,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            <small class="muted">Format: PDF, Word, JPG, PNG, WEBP. Maksimal 5 MB.</small>
        </label>
        <?php if (!empty($edit['proof_path'])): ?>
            <div class="span-2 upload-list">
                <strong>Bukti Tersimpan</strong>
                <div class="upload-item">
                    <a href="<?= file_link($edit['proof_path']) ?>" target="_blank"><?= e($edit['proof_original_name']) ?></a>
                    <small class="muted">Upload file baru untuk mengganti bukti ini.</small>
                </div>
            </div>
        <?php endif; ?>
        <label class="span-2">Catatan
            <textarea name="notes"><?= e($edit['notes'] ?? '') ?></textarea>
        </label>
        <div class="actions span-2">
            <button class="btn primary" type="submit">Simpan</button>
            <a class="btn ghost" href="index.php?action=payments">Batal</a>
        </div>
    </form>
</section>
<?php else: ?>

<section class="table-wrap">
    <div class="section-head">
        <h2>Riwayat Pembayaran</h2>
        <div class="table-tools">
            <form class="search-form" method="get" action="index.php">
                <input type="hidden" name="action" value="payments">
                <input name="q" placeholder="Cari perkara, klien, metode, catatan" value="<?= e($q ?? '') ?>">
                <button class="btn primary" type="submit">Cari</button>
                <a class="btn ghost" href="index.php?action=payments">Reset</a>
            </form>
            <a class="btn warning" href="index.php?action=payment_form">Tambah Data</a>
        </div>
    </div>
    <table>
        <thead>
            <tr><th>Tanggal</th><th>No. Perkara</th><th>Klien</th><th>Jumlah</th><th>Metode</th><th>Bukti</th><th>Catatan</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= e($payment['payment_date']) ?></td>
                    <td><?= e($payment['case_number']) ?><br><small><?= e($payment['title']) ?></small></td>
                    <td><?= e($payment['client_name']) ?></td>
                    <td><?= rupiah((float) $payment['amount']) ?></td>
                    <td><?= e($payment['method']) ?></td>
                    <td>
                        <?php if (!empty($payment['proof_path'])): ?>
                            <a class="file-pill" href="<?= file_link($payment['proof_path']) ?>" target="_blank"><?= e($payment['proof_original_name']) ?></a>
                        <?php else: ?>
                            <small>Belum ada</small>
                        <?php endif; ?>
                    </td>
                    <td><?= e($payment['notes']) ?></td>
                    <td class="actions">
                        <a class="btn warning" href="index.php?action=payment_form&id=<?= $payment['id'] ?>">Edit</a>
                        <a class="btn danger" href="index.php?action=payment_delete&id=<?= $payment['id'] ?>" onclick="return confirm('Hapus pembayaran ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$payments): ?>
                <tr><td colspan="8">Belum ada data pembayaran.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>
