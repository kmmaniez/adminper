<section class="form-panel">
    <h2><?= $edit ? 'Edit Pengacara' : 'Tambah Pengacara' ?></h2>
    <form class="form" method="post" action="index.php?action=lawyer_save">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>Nama
            <input name="name" required value="<?= e($edit['name'] ?? '') ?>">
        </label>
        <label>Email
            <input type="email" name="email" required value="<?= e($edit['email'] ?? '') ?>">
        </label>
        <label>Password <?= $edit ? '(kosongkan jika tidak diganti)' : '' ?>
            <input type="password" name="password" <?= $edit ? '' : 'required' ?>>
        </label>
        <label>No. HP
            <input name="phone" value="<?= e($edit['phone'] ?? '') ?>">
        </label>
        <label>Spesialisasi
            <input name="specialization" value="<?= e($edit['specialization'] ?? '') ?>">
        </label>
        <label class="span-2">Alamat
            <textarea name="address"><?= e($edit['address'] ?? '') ?></textarea>
        </label>
        <div class="actions span-2">
            <button class="btn primary" type="submit">Simpan</button>
            <a class="btn ghost" href="index.php?action=lawyers">Batal</a>
        </div>
    </form>
</section>

<section class="table-wrap">
    <h2>Daftar Pengacara</h2>
    <table>
        <thead>
            <tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Spesialisasi</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($lawyers as $lawyer): ?>
                <tr>
                    <td><?= e($lawyer['name']) ?></td>
                    <td><?= e($lawyer['email']) ?></td>
                    <td><?= e($lawyer['phone']) ?></td>
                    <td><?= e($lawyer['specialization']) ?></td>
                    <td class="actions">
                        <a class="btn warning" href="index.php?action=lawyers&id=<?= $lawyer['id'] ?>">Edit</a>
                        <a class="btn danger" href="index.php?action=lawyer_delete&id=<?= $lawyer['id'] ?>" onclick="return confirm('Hapus pengacara ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$lawyers): ?>
                <tr><td colspan="5">Belum ada data pengacara.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
