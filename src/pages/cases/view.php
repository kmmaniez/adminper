<?php
$documentStatuses = ['sangat lengkap', 'lengkap', 'cukup lengkap', 'kurang lengkap', 'tidak lengkap'];
$administrationStatuses = ['belum diproses', 'berkas sudah didaftarkan', 'menunggu jadwal sidang', 'sidang sedang berlangsung', 'perkara selesai'];
?>
<?php if ($showForm ?? false): ?>
<section class="form-panel">
    <h2><?= $edit ? 'Edit Perkara' : 'Tambah Perkara' ?></h2>
    <form class="form" method="post" action="index.php?action=case_save" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>No. Perkara
            <input name="case_number" required value="<?= e($edit['case_number'] ?? '') ?>">
        </label>
        <label>Judul Perkara
            <input name="title" required value="<?= e($edit['title'] ?? '') ?>">
        </label>
        <label>Klien
            <select name="client_id" required>
                <option value="">Pilih klien</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>" <?= (int) ($edit['client_id'] ?? 0) === (int) $client['id'] ? 'selected' : '' ?>><?= e($client['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Pengacara
            <select name="lawyer_id">
                <option value="">Belum ditentukan</option>
                <?php foreach ($lawyers as $lawyer): ?>
                    <option value="<?= $lawyer['id'] ?>" <?= (int) ($edit['lawyer_id'] ?? 0) === (int) $lawyer['id'] ? 'selected' : '' ?>><?= e($lawyer['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Kategori Perkara
            <select name="category_id" required>
                <option value="">Pilih kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= (int) ($edit['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Jadwal Sidang
            <input type="date" name="hearing_date" value="<?= e($edit['hearing_date'] ?? '') ?>">
        </label>
        <label>Kelengkapan Berkas
            <select name="document_status" required>
                <?php foreach ($documentStatuses as $status): ?>
                    <option value="<?= e($status) ?>" <?= ($edit['document_status'] ?? 'cukup lengkap') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Status Administrasi
            <select name="administration_status" required>
                <?php foreach ($administrationStatuses as $status): ?>
                    <option value="<?= e($status) ?>" <?= ($edit['administration_status'] ?? 'belum diproses') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Total Biaya
            <input type="number" name="total_fee" min="0" step="1000" required value="<?= e((string) ($edit['total_fee'] ?? 0)) ?>">
        </label>
        <label class="span-2">Catatan
            <textarea name="notes"><?= e($edit['notes'] ?? '') ?></textarea>
        </label>
        <label class="span-2">Upload Dokumen Perkara
            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,image/jpeg,image/png,image/webp,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            <small class="muted">Bisa upload banyak dokumen. Format: PDF, Word, JPG, PNG, WEBP. Maksimal 5 MB per file.</small>
        </label>
        <?php if ($edit && $caseDocuments): ?>
            <div class="span-2 upload-list">
                <strong>Dokumen Tersimpan</strong>
                <?php foreach ($caseDocuments as $document): ?>
                    <div class="upload-item">
                        <a href="<?= file_link($document['stored_path']) ?>" target="_blank"><?= e($document['original_name']) ?></a>
                        <a class="btn danger" href="index.php?action=case_document_delete&id=<?= $document['id'] ?>&case_id=<?= $edit['id'] ?>" onclick="return confirm('Hapus dokumen ini?')">Hapus</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="actions span-2">
            <button class="btn primary" type="submit">Simpan</button>
            <a class="btn ghost" href="index.php?action=cases">Batal</a>
        </div>
    </form>
</section>
<?php else: ?>

<section class="table-wrap">
    <div class="section-head">
        <h2>Daftar Perkara</h2>
        <div class="table-tools">
            <form class="search-form" method="get" action="index.php">
                <input type="hidden" name="action" value="cases">
                <input name="q" placeholder="Cari no perkara, klien, pengacara, status" value="<?= e($q ?? '') ?>">
                <button class="btn primary" type="submit">Cari</button>
                <a class="btn ghost" href="index.php?action=cases">Reset</a>
            </form>
            <a class="btn warning" href="index.php?action=case_form">Tambah Data</a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>No. Perkara</th>
                <th>Klien</th>
                <th>Pengacara</th>
                <th>Kategori</th>
                <th>Sidang</th>
                <th>Status</th>
                <th>Dokumen</th>
                <th>Total Biaya</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cases as $case): ?>
                <tr>
                    <td><?= e($case['case_number']) ?><br><small><?= e($case['title']) ?></small></td>
                    <td><?= e($case['client_name']) ?></td>
                    <td><?= e($case['lawyer_name'] ?? '-') ?></td>
                    <td><?= e($case['category_name']) ?></td>
                    <td><?= e($case['hearing_date'] ?: 'Belum ada') ?></td>
                    <td><?= e($case['administration_status']) ?><br><small><?= e($case['document_status']) ?></small></td>
                    <td>
                        <?php
                        $stmt = db()->prepare('SELECT * FROM case_documents WHERE case_id = ? ORDER BY created_at DESC');
                        $stmt->execute([(int) $case['id']]);
                        $documents = $stmt->fetchAll();
                        ?>
                        <?php if ($documents): ?>
                            <a class="btn ghost doc-view-btn" href="index.php?action=case_documents&id=<?= $case['id'] ?>">
                                <span class="icon-eye"></span>
                                Lihat Dokumen
                            </a>
                            <small><?= count($documents) ?> dokumen</small>
                        <?php else: ?>
                            <small>Belum ada</small>
                        <?php endif; ?>
                    </td>
                    <td><?= rupiah((float) $case['total_fee']) ?></td>
                    <td class="actions">
                        <a class="btn warning" href="index.php?action=case_form&id=<?= $case['id'] ?>">Edit</a>
                        <a class="btn danger" href="index.php?action=case_delete&id=<?= $case['id'] ?>" onclick="return confirm('Hapus perkara ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$cases): ?>
                <tr><td colspan="9">Belum ada data perkara.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>
