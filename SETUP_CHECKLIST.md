# Setup Checklist - Admin Panel

Ikuti checklist ini untuk setup admin panel dengan sempurna.

---

## ✅ Tahap 1: Database Setup

- [ ] **Pastikan XAMPP sudah running**
  - Start Apache
  - Start MySQL
  
- [ ] **Buka phpMyAdmin**
  - Buka browser: `http://localhost/phpmyadmin`
  - Login dengan username `root` (biasanya no password)

- [ ] **Import Database**
  - Di phpMyAdmin, klik "Import"
  - Pilih file: `md/db.sql`
  - Klik "Go"
  - Tunggu import selesai
  - Cek database `db_jasa_dekorasi_pernikahan` sudah ada

- [ ] **Verifikasi Tabel**
  - Klik database `db_jasa_dekorasi_pernikahan`
  - Cek tabel yang ada:
    - `tb_admin` (kosong, untuk login)
    - `tb_kategori` (kosong)
    - `tb_paket` (kosong)
    - `tb_gambar_produk` (kosong)
    - `tb_portofolio` (kosong)
    - `tb_testimoni` (kosong)
    - `tb_pesan` (kosong)

---

## ✅ Tahap 2: File & Folder Setup

- [ ] **Cek struktur folder**
  ```
  1_website_jasa_dekorasi_pernikahan_event/
  ├── admin/
  │   ├── auth/
  │   │   ├── login.php
  │   │   ├── register.php
  │   │   ├── session.php
  │   │   └── logout.php
  │   ├── pages/
  │   │   ├── dashboard.php
  │   │   ├── kategori.php
  │   │   ├── paket.php
  │   │   ├── gambar.php
  │   │   ├── portofolio.php
  │   │   ├── testimoni.php
  │   │   └── pesan.php
  │   ├── uploads/ (folder untuk gambar)
  │   ├── dashboard.php
  │   └── README.md
  ├── md/
  │   ├── db.sql
  │   └── design.md
  ├── config.php
  ├── index.php
  ├── ADMIN_PANEL_GUIDE.md
  └── SETUP_CHECKLIST.md (file ini)
  ```

- [ ] **Cek file config.php sudah dibuat**
  - File: `config.php`
  - Cek isi konfigurasi database:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'db_jasa_dekorasi_pernikahan');
    ```

- [ ] **Buat folder uploads jika belum**
  - Folder: `admin/uploads/`
  - Pastikan writable (permission 755 di Linux/Mac atau full access di Windows)

---

## ✅ Tahap 3: Browser Test

- [ ] **Test Login Page**
  - Buka: `http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/login.php`
  - Pastikan halaman loading dengan benar
  - Cek desain sudah sesuai dengan Tailwind CSS

- [ ] **Test Register Page**
  - Klik link "Daftar di sini"
  - Buka: `http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/register.php`
  - Pastikan halaman loading dengan benar

---

## ✅ Tahap 4: Registrasi Akun Admin

- [ ] **Daftar akun admin pertama**
  - Buka halaman register
  - Isi form:
    - Username: `admin` (atau yang lain)
    - Password: `admin123` (atau minimal 6 karakter)
    - Konfirmasi: `admin123`
  - Klik "Daftar"
  - Cek pesan sukses

- [ ] **Verifikasi di Database**
  - Buka phpMyAdmin
  - Klik tabel `tb_admin`
  - Cek ada 1 row dengan username yang baru dibuat
  - Cek password sudah ter-hash (bukan plain text)

---

## ✅ Tahap 5: Login Test

- [ ] **Test Login Berhasil**
  - Buka login page
  - Masukkan username dan password yang baru dibuat
  - Klik "Masuk"
  - Pastikan redirect ke dashboard

- [ ] **Test Dashboard Loaded**
  - Cek sidebar menu ada
  - Cek header dengan username tertampil
  - Cek statistics cards (seharusnya semua 0 dulu)
  - Cek quick actions buttons

- [ ] **Test Session Protection**
  - Jika logout, coba akses dashboard langsung via URL
  - Seharusnya redirect ke login page

---

## ✅ Tahap 6: Menu Navigation Test

- [ ] **Test Kategori Menu**
  - Klik "Kategori" di sidebar
  - Halaman berisi form tambah kategori
  - Dan tabel list kategori (kosong dulu)

- [ ] **Test Paket Menu**
  - Klik "Paket Dekorasi" di sidebar
  - Halaman berisi form tambah paket
  - Dan list paket (kosong dulu)

- [ ] **Test Gambar Menu**
  - Klik "Gambar Produk" di sidebar
  - Halaman berisi form upload gambar
  - Dan tabel list gambar (kosong dulu)

- [ ] **Test Portofolio Menu**
  - Klik "Portofolio" di sidebar
  - Halaman berisi form tambah portofolio

- [ ] **Test Testimoni Menu**
  - Klik "Testimoni" di sidebar
  - Halaman berisi form tambah testimoni

- [ ] **Test Pesan Menu**
  - Klik "Pesan Masuk" di sidebar
  - Halaman berisi statistik pesan
  - Dan list pesan (kosong dulu)

---

## ✅ Tahap 7: Data Entry Test

- [ ] **Tambah Kategori**
  - Menu → Kategori
  - Isi form:
    - Nama Kategori: "Traditional"
    - Slug: "traditional"
  - Klik "Tambah Kategori"
  - Cek pesan sukses
  - Cek data muncul di tabel

- [ ] **Tambah Paket**
  - Menu → Paket Dekorasi
  - Isi form:
    - Kategori: "Traditional"
    - Nama Paket: "Paket Classic"
    - Harga: 5000000
    - Deskripsi: "Paket dekorasi tradisional dengan konsep klasik"
    - Fitur: "Bunga segar\nKursi Dekorasi\nLampion"
    - Foto: Upload file gambar (JPG/PNG)
  - Klik "Tambah Paket"
  - Cek pesan sukses
  - Cek data muncul di list

- [ ] **Tambah Gambar Produk**
  - Menu → Gambar Produk
  - Isi form:
    - Paket: "Paket Classic"
    - Nama Gambar: "Tampak Depan"
    - Urutan: 1
    - File: Upload gambar
  - Klik "Upload Gambar"
  - Cek data muncul di tabel

- [ ] **Verifikasi di Database**
  - phpMyAdmin → tabel `tb_kategori` (ada 1 row)
  - phpMyAdmin → tabel `tb_paket` (ada 1 row)
  - phpMyAdmin → tabel `tb_gambar_produk` (ada 1 row)

---

## ✅ Tahap 8: File Upload Test

- [ ] **Cek Folder Uploads**
  - Buka: `admin/uploads/`
  - Seharusnya ada file gambar yang di-upload
  - Nama file: `paket_[timestamp].jpg` atau `produk_[timestamp].jpg`

- [ ] **Cek File Permissions**
  - Pastikan file bisa dibaca
  - Di Windows: biasanya otomatis OK
  - Di Linux: `chmod 644 admin/uploads/*`

---

## ✅ Tahap 9: Logout & Session Test

- [ ] **Test Logout**
  - Klik tombol "Logout" di sidebar
  - Seharusnya redirect ke login page
  - Session sudah dihapus

- [ ] **Test Session Expiry**
  - Login ulang
  - Buka browser dev tools (F12)
  - Cek tab "Application" → "Cookies"
  - Seharusnya ada cookie `PHPSESSID`

---

## ✅ Tahap 10: Documentation & Backup

- [ ] **Baca Dokumentasi**
  - Buka file: `ADMIN_PANEL_GUIDE.md`
  - Pahami struktur database
  - Pahami fitur setiap menu

- [ ] **Backup Database**
  ```bash
  # Di command line / terminal
  mysqldump -u root -p db_jasa_dekorasi_pernikahan > backup_initial.sql
  ```

- [ ] **Simpan Password**
  - Catat username dan password admin
  - Simpan di tempat aman (file txt atau password manager)

---

## ✅ Tahap 11: Customization (Opsional)

- [ ] **Customize Warna**
  - Edit file `pages/dashboard.php`
  - Ubah Tailwind classes sesuai preferensi
  - Contoh: `bg-rose-600` → `bg-purple-600`

- [ ] **Customize Logo**
  - Edit teks "Admin" di sidebar header
  - Bisa mengganti dengan logo HTML/image

- [ ] **Setup Meta Tags**
  - Edit title dan meta tags di setiap halaman
  - Tambahkan favicon jika ingin

---

## ✅ Troubleshooting

### Jika Database Error
- [ ] Cek MySQL running: `http://localhost/phpmyadmin`
- [ ] Cek config.php credentials benar
- [ ] Cek database dan tabel sudah dibuat

### Jika File Not Found
- [ ] Cek file path di URL benar
- [ ] Cek file sudah dibuat (di file explorer)
- [ ] Cek spelling dan case sensitivity

### Jika Upload Gambar Gagal
- [ ] Cek folder `admin/uploads/` exists
- [ ] Cek folder writable (permission 755)
- [ ] Cek file size < 2MB
- [ ] Cek format JPG/PNG/GIF

### Jika Login Gagal
- [ ] Cek username/password benar
- [ ] Cek cookies browser aktif
- [ ] Clear browser cache dan coba lagi

---

## ✅ Finishing

- [ ] Semua checkbox di atas sudah ✓
- [ ] Admin panel berfungsi dengan baik
- [ ] Ready untuk production/live

---

**Congratulations! Admin panel setup completed!** 🎉

Sekarang Anda bisa:
1. Kelola kategori dekorasi
2. Kelola paket dengan harga
3. Upload gambar produk
4. Kelola portofolio
5. Kelola testimoni
6. Menerima pesan dari pelanggan

Lanjutkan dengan membuat homepage yang menampilkan data dari database!
