<section class="form-panel">
    <h2>Update Data Pribadi</h2>
    <form class="form" method="post" action="index.php?action=profile_save">
        <label>Nama
            <input name="name" required value="<?= e($edit['name'] ?? '') ?>">
        </label>
        <label>Email
            <input type="email" name="email" required value="<?= e($edit['email'] ?? '') ?>">
        </label>
        <label>Password Baru
            <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
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
            <button class="btn primary" type="submit">Simpan Data Pribadi</button>
        </div>
    </form>
</section>
