<?php if ($showForm ?? false): ?>
<section class="form-panel">
    <h2><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h2>
    <form class="form" method="post" action="index.php?action=category_save">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>Nama Kategori
            <input name="name" required value="<?= e($edit['name'] ?? '') ?>">
        </label>
        <label>Skala SAW C2
            <select name="saw_score" required>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>" <?= (int) ($edit['saw_score'] ?? 0) === $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </label>
        <div class="actions span-2">
            <button class="btn primary" type="submit">Simpan</button>
            <a class="btn ghost" href="index.php?action=categories">Batal</a>
        </div>
    </form>
</section>
<?php else: ?>

<section class="table-wrap">
    <div class="section-head">
        <h2>Daftar Kategori</h2>
        <div class="table-tools">
            <form class="search-form" method="get" action="index.php">
                <input type="hidden" name="action" value="categories">
                <input name="q" placeholder="Cari kategori atau skala" value="<?= e($q ?? '') ?>">
                <button class="btn primary" type="submit">Cari</button>
                <a class="btn ghost" href="index.php?action=categories">Reset</a>
            </form>
            <a class="btn warning" href="index.php?action=category_form">Tambah Data</a>
        </div>
    </div>
    <table>
        <thead>
            <tr><th>Kategori</th><th>Skala C2</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= e($category['name']) ?></td>
                    <td><?= (int) $category['saw_score'] ?></td>
                    <td class="actions">
                        <a class="btn warning" href="index.php?action=category_form&id=<?= $category['id'] ?>">Edit</a>
                        <a class="btn danger" href="index.php?action=category_delete&id=<?= $category['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$categories): ?>
                <tr><td colspan="3">Belum ada kategori perkara.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>
