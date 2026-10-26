<section class="form-panel">
    <div class="section-head">
        <div>
            <h2>Dokumen Perkara</h2>
            <p class="muted"><?= e($case['case_number']) ?> - <?= e($case['title']) ?></p>
        </div>
        <div class="actions">
            <a class="btn warning" href="index.php?action=case_form&id=<?= $case['id'] ?>">Tambah / Edit Dokumen</a>
            <a class="btn ghost" href="index.php?action=cases">Kembali</a>
        </div>
    </div>
</section>

<section class="table-wrap">
    <div class="section-head">
        <h2>Daftar Dokumen</h2>
        <form class="search-form" method="get" action="index.php">
            <input type="hidden" name="action" value="case_documents">
            <input type="hidden" name="id" value="<?= $case['id'] ?>">
            <input name="q" placeholder="Cari nama berkas" value="<?= e($q ?? '') ?>">
            <button class="btn primary" type="submit">Cari</button>
            <a class="btn ghost" href="index.php?action=case_documents&id=<?= $case['id'] ?>">Reset</a>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama Dokumen</th>
                <th>Tipe</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $document): ?>
                <tr>
                    <td><?= e($document['original_name']) ?></td>
                    <td><?= e($document['mime_type']) ?></td>
                    <td><?= e(date_id($document['created_at'])) ?></td>
                    <td class="actions">
                        <a class="btn primary" href="<?= file_link($document['stored_path']) ?>" target="_blank">
                            <span class="icon-eye light"></span>
                            Buka
                        </a>
                        <a class="btn danger" href="index.php?action=case_document_delete&id=<?= $document['id'] ?>&case_id=<?= $case['id'] ?>" onclick="return confirm('Hapus dokumen ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$documents): ?>
                <tr><td colspan="4">Tidak ada dokumen yang sesuai.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
