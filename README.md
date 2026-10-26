# Website Administrasi Perkara

Aplikasi administrasi perkara untuk **Kantor Hukum Rotama, S.H & Rekan** berbasis PHP native dan MySQL, dirancang untuk dijalankan dengan Laragon, Visual Studio Code, dan phpMyAdmin.

## Cara Menjalankan di Laragon

1. Salin folder proyek ini ke:
   `C:\laragon\www\administrasi-perkara`
2. Buka Laragon, jalankan Apache dan MySQL.
3. Buka phpMyAdmin, buat database:
   `administrasi_perkara`
4. Import file:
   `database/schema.sql`
5. Sesuaikan koneksi database di:
   `config/database.php`
6. Buka browser:
   `http://administrasi-perkara.test`
   atau
   `http://localhost/administrasi-perkara/public`

## Akun Demo

- Admin
  - Email: `admin@rotama.test`
  - Password: `password`
- Pengacara
  - Email: `pengacara@rotama.test`
  - Password: `password`

## Fitur

- Login role admin dan pengacara
- Dashboard dengan metode SAW
- Kelola data pengacara khusus admin dengan pencarian
- Update data pribadi untuk admin dan pengacara
- Kelola data klien dengan pencarian
- Kelola data perkara dengan pencarian
- Halaman tambah/edit data dibuat terpisah dari halaman tabel
- Upload banyak dokumen PDF/gambar untuk setiap perkara
- Kelola kategori perkara dengan pencarian
- Rekap perkara format laporan bulanan dengan filter dan cetak
- Pembayaran bertahap/cicilan dengan bukti pembayaran per transaksi dan pencarian
- Total bayar per perkara dan grand total

## Hak Akses

- Admin dapat mengelola semua data, termasuk data pengacara.
- Pengacara tidak dapat mengelola data pengacara, tetapi dapat memperbarui data pribadi.

## Revisi Upload Dokumen

Jika database sudah pernah di-import sebelum fitur upload dibuat, import file ini sekali lewat phpMyAdmin:

`database/migration_upload_dokumen.sql`

File upload tersimpan otomatis di:

- `public/uploads/case-documents`
- `public/uploads/payment-proofs`

Format yang diterima:

- PDF
- JPG/JPEG
- PNG
- WEBP

Ukuran maksimal per file: 5 MB.
# adminper
