<section class="form-panel">
    <h2><?= $edit ? 'Edit Klien' : 'Tambah Klien' ?></h2>
    <form class="form" method="post" action="index.php?action=client_save">
        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>Nama Klien
            <input name="name" required value="<?= e($edit['name'] ?? '') ?>">
        </label>
        <label>NIK
            <input name="nik" value="<?= e($edit['nik'] ?? '') ?>">
        </label>
        <label>No. HP
            <input name="phone" value="<?= e($edit['phone'] ?? '') ?>">
        </label>
        <label class="span-2">Alamat
            <textarea name="address"><?= e($edit['address'] ?? '') ?></textarea>
        </label>
        <div class="actions span-2">
            <button class="btn primary" type="submit">Simpan</button>
            <a class="btn ghost" href="index.php?action=clients">Batal</a>
        </div>
    </form>
</section>

<section class="table-wrap">
    <h2>Daftar Klien</h2>
    <table>
        <thead>
            <tr><th>Nama</th><th>NIK</th><th>No. HP</th><th>Alamat</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= e($client['name']) ?></td>
                    <td><?= e($client['nik']) ?></td>
                    <td><?= e($client['phone']) ?></td>
                    <td><?= e($client['address']) ?></td>
                    <td class="actions">
                        <a class="btn warning" href="index.php?action=clients&id=<?= $client['id'] ?>">Edit</a>
                        <a class="btn danger" href="index.php?action=client_delete&id=<?= $client['id'] ?>" onclick="return confirm('Hapus klien ini? Semua perkara milik klien ikut terhapus.')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$clients): ?>
                <tr><td colspan="5">Belum ada data klien.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
