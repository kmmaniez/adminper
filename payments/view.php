<section class="form-panel">
    <h2><?= $edit ? 'Edit Pembayaran' : 'Tambah Pembayaran' ?></h2>
    <form class="form" method="post" action="index.php?action=payment_save">
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
        <label class="span-2">Catatan
            <textarea name="notes"><?= e($edit['notes'] ?? '') ?></textarea>
        </label>
        <div class="actions span-2">
            <button class="btn primary" type="submit">Simpan</button>
            <a class="btn ghost" href="index.php?action=payments">Batal</a>
        </div>
    </form>
</section>

<section class="table-wrap">
    <h2>Riwayat Pembayaran</h2>
    <table>
        <thead>
            <tr><th>Tanggal</th><th>No. Perkara</th><th>Klien</th><th>Jumlah</th><th>Metode</th><th>Catatan</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= e($payment['payment_date']) ?></td>
                    <td><?= e($payment['case_number']) ?><br><small><?= e($payment['title']) ?></small></td>
                    <td><?= e($payment['client_name']) ?></td>
                    <td><?= rupiah((float) $payment['amount']) ?></td>
                    <td><?= e($payment['method']) ?></td>
                    <td><?= e($payment['notes']) ?></td>
                    <td class="actions">
                        <a class="btn warning" href="index.php?action=payments&id=<?= $payment['id'] ?>">Edit</a>
                        <a class="btn danger" href="index.php?action=payment_delete&id=<?= $payment['id'] ?>" onclick="return confirm('Hapus pembayaran ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$payments): ?>
                <tr><td colspan="7">Belum ada data pembayaran.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
