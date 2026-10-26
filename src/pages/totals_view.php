<?php
$grandFee = 0;
$grandPaid = 0;
?>
<section class="table-wrap">
    <h2>Ringkasan Total Bayar</h2>
    <table>
        <thead>
            <tr>
                <th>No. Perkara</th>
                <th>Klien</th>
                <th>Pengacara</th>
                <th>Total Biaya</th>
                <th>Sudah Bayar</th>
                <th>Sisa</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <?php
                $fee = (float) $row['total_fee'];
                $paid = (float) $row['paid_total'];
                $remaining = max($fee - $paid, 0);
                $grandFee += $fee;
                $grandPaid += $paid;
                ?>
                <tr>
                    <td><?= e($row['case_number']) ?><br><small><?= e($row['title']) ?></small></td>
                    <td><?= e($row['client_name']) ?></td>
                    <td><?= e($row['lawyer_name'] ?? '-') ?></td>
                    <td><?= rupiah($fee) ?></td>
                    <td><?= rupiah($paid) ?></td>
                    <td><?= rupiah($remaining) ?></td>
                    <td><span class="badge"><?= e(payment_status_label($fee, $paid)) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
                <tr><td colspan="7">Belum ada data total bayar.</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Grand Total</th>
                <th><?= rupiah($grandFee) ?></th>
                <th><?= rupiah($grandPaid) ?></th>
                <th><?= rupiah(max($grandFee - $grandPaid, 0)) ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</section>
