# Admin Panel - Panduan Lengkap

## Daftar Isi
1. [Fitur Utama](#fitur-utama)
2. [Cara Mengakses](#cara-mengakses)
3. [Autentikasi](#autentikasi)
4. [Menu dan Fungsi](#menu-dan-fungsi)
5. [Struktur Folder](#struktur-folder)
6. [Database yang Digunakan](#database-yang-digunakan)

---

## Fitur Utama

Admin panel ini dirancang untuk mengelola seluruh konten website jasa dekorasi pernikahan dengan antarmuka yang elegan dan mudah digunakan.

**Fitur Unggulan:**
- ✅ Sistem Login/Register dengan session management
- ✅ Dashboard dengan statistik real-time
- ✅ Kelola kategori dekorasi
- ✅ Kelola paket dekorasi dengan upload foto
- ✅ Upload dan kelola gambar produk
- ✅ Kelola portofolio/galeri
- ✅ Kelola testimoni pelanggan
- ✅ Sistem pesan masuk dengan status baca

---

## Cara Mengakses

### URL Admin Panel
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/login.php
```

### Akses Dari Halaman Utama
Biasanya ada link ke admin di footer atau halaman khusus.

---

## Autentikasi

### 1. **Registrasi (Daftar Akun)**

**URL:** `admin/auth/register.php`

Langkah-langkah:
1. Masuk ke halaman registrasi
2. Isi form:
   - **Username:** 3-50 karakter (alfanumerik)
   - **Password:** Minimal 6 karakter
   - **Konfirmasi Password:** Harus cocok dengan password
3. Klik "Daftar"
4. Jika berhasil, akan muncul pesan sukses
5. Redirect ke login page

**Validasi:**
- Username harus unik (tidak boleh duplikat)
- Username minimal 3 karakter, maksimal 50 karakter
- Password minimal 6 karakter
- Password dan konfirmasi harus sama

### 2. **Login (Masuk)**

**URL:** `admin/auth/login.php`

Langkah-langkah:
1. Masuk ke halaman login
2. Isi form:
   - **Username:** Username yang sudah terdaftar
   - **Password:** Password yang benar
3. Klik "Masuk"
4. Jika berhasil, akan masuk ke dashboard
5. Session dimulai (cookie akan disimpan)

**Keamanan Session:**
- Password di-hash menggunakan `PASSWORD_BCRYPT`
- Session ID disimpan di database
- Jika sudah login, tidak bisa kembali ke login/register
- Redirect otomatis ke dashboard

### 3. **Logout (Keluar)**

**Tombol:** Di sidebar bawah, klik "Logout"

Efek:
- Session dihapus
- Cookie session dihapus
- Redirect ke login page
- Harus login lagi untuk akses dashboard

---

## Menu dan Fungsi

### Dashboard
- **Lokasi:** `admin/dashboard.php`
- **Fungsi:** Overview admin dengan statistik
- **Konten:**
  - Total kategori
  - Total paket
  - Total gambar
  - Pesan belum dibaca
  - Aksi cepat ke setiap menu

### Kategori
- **Lokasi:** `admin/pages/kategori.php`
- **Fungsi:** Mengelola kategori dekorasi (Traditional, Modern, Rustic, dll)
- **Fitur:**
  - Tambah kategori baru
  - Hapus kategori
  - Lihat daftar kategori
- **Data yang disimpan:**
  - Nama kategori
  - Slug (untuk URL-friendly)

### Paket Dekorasi
- **Lokasi:** `admin/pages/paket.php`
- **Fungsi:** Mengelola paket dekorasi dengan harga dan fitur
- **Fitur:**
  - Tambah paket baru
  - Pilih kategori
  - Upload foto paket
  - Isi deskripsi dan fitur
  - Hapus paket
- **Data yang disimpan:**
  - ID kategori
  - Nama paket
  - Harga (Rp)
  - Deskripsi
  - Fitur/Fasilitas
  - Foto paket

### Gambar Produk
- **Lokasi:** `admin/pages/gambar.php`
- **Fungsi:** Upload dan kelola gambar produk untuk setiap paket
- **Fitur:**
  - Upload gambar ke paket tertentu
  - Beri nama untuk setiap gambar
  - Atur urutan gambar
  - Lihat daftar semua gambar
  - Hapus gambar
- **Data yang disimpan:**
  - ID paket
  - Nama gambar
  - Path file gambar
  - Urutan gambar
  - Tanggal upload

### Portofolio
- **Lokasi:** `admin/pages/portofolio.php`
- **Fungsi:** Kelola galeri/portofolio acara yang sudah dikerjakan
- **Fitur:**
  - Tambah portofolio baru
  - Upload foto portofolio
  - Isi tanggal event
  - Isi deskripsi acara
  - Hapus portofolio
- **Data yang disimpan:**
  - Judul portofolio
  - Deskripsi
  - Foto
  - Tanggal event

### Testimoni
- **Lokasi:** `admin/pages/testimoni.php`
- **Fungsi:** Kelola ulasan dan rating dari klien
- **Fitur:**
  - Tambah testimoni baru
  - Atur rating bintang (1-5)
  - Upload foto klien
  - Hapus testimoni
- **Data yang disimpan:**
  - Nama klien
  - Ulasan/Review
  - Rating bintang
  - Foto klien (opsional)

### Pesan Masuk
- **Lokasi:** `admin/pages/pesan.php`
- **Fungsi:** Melihat dan mengelola pesan dari pelanggan
- **Fitur:**
  - Filter pesan (Semua, Belum Dibaca, Sudah Dibaca)
  - Tandai pesan sebagai sudah dibaca/belum
  - Hapus pesan
  - Lihat detail pesan lengkap
  - Hubungi via Email atau WhatsApp
- **Statistik:**
  - Total pesan
  - Pesan belum dibaca (dengan badge merah)
  - Pesan sudah dibaca

---

## Struktur Folder

```
admin/
├── auth/
│   ├── login.php          (Halaman login)
│   ├── register.php       (Halaman registrasi)
│   ├── session.php        (Manajemen session)
│   └── logout.php         (Script logout)
├── pages/
│   ├── dashboard.php      (Dashboard overview)
│   ├── kategori.php       (Kelola kategori)
│   ├── paket.php          (Kelola paket)
│   ├── gambar.php         (Kelola gambar produk)
│   ├── portofolio.php     (Kelola portofolio)
│   ├── testimoni.php      (Kelola testimoni)
│   └── pesan.php          (Kelola pesan masuk)
├── uploads/               (Folder upload gambar)
└── dashboard.php          (Main dashboard file)

Root Folder:
├── config.php             (Konfigurasi database)
└── ADMIN_PANEL_GUIDE.md   (File ini)
```

---

## Database yang Digunakan

### Tabel tb_admin
Menyimpan data admin untuk login/register
```sql
CREATE TABLE tb_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
```

### Tabel tb_kategori
Menyimpan kategori dekorasi
```sql
CREATE TABLE tb_kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);
```

### Tabel tb_paket
Menyimpan paket dekorasi
```sql
CREATE TABLE tb_paket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT,
    nama_paket VARCHAR(150) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    deskripsi TEXT,
    fitur TEXT,
    foto VARCHAR(255),
    FOREIGN KEY (id_kategori) REFERENCES tb_kategori(id)
);
```

### Tabel tb_gambar_produk
Menyimpan gambar produk untuk setiap paket
```sql
CREATE TABLE tb_gambar_produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_paket INT NOT NULL,
    nama_gambar VARCHAR(255) NOT NULL,
    path_gambar VARCHAR(255) NOT NULL,
    urutan INT DEFAULT 1,
    tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_paket) REFERENCES tb_paket(id) ON DELETE CASCADE
);
```

### Tabel tb_portofolio
Menyimpan portofolio/galeri
```sql
CREATE TABLE tb_portofolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255),
    tanggal_event DATE
);
```

### Tabel tb_testimoni
Menyimpan testimoni pelanggan
```sql
CREATE TABLE tb_testimoni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_klien VARCHAR(100) NOT NULL,
    ulasan TEXT NOT NULL,
    bintang INT DEFAULT 5,
    foto_klien VARCHAR(255) NULL
);
```

### Tabel tb_pesan
Menyimpan pesan masuk
```sql
CREATE TABLE tb_pesan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pengirim VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    no_whatsapp VARCHAR(20) NOT NULL,
    pesan TEXT NOT NULL,
    status_baca ENUM('belum', 'sudah') DEFAULT 'belum',
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Konfigurasi Database

File: `config.php`

Sesuaikan dengan konfigurasi XAMPP Anda:
```php
define('DB_HOST', 'localhost');    // Host MySQL
define('DB_USER', 'root');         // Username MySQL
define('DB_PASS', '');             // Password MySQL
define('DB_NAME', 'db_jasa_dekorasi_pernikahan');  // Nama database
```

---

## Tips Penggunaan

### Upload Gambar
- Gunakan format JPG, PNG, atau GIF
- Disarankan ukuran maksimal 2MB per file
- Gambar akan disimpan di folder `admin/uploads/`
- Gunakan nama file yang deskriptif

### Nama Slug
- Gunakan huruf kecil (lowercase)
- Gunakan tanda hubung (-) untuk spasi
- Contoh: `traditional-wedding`, `modern-minimalist`
- Slug harus unik (tidak boleh duplikat)

### Rating Bintang
- Nilai 1-5 untuk testimoni
- Ditampilkan dengan icon ⭐
- Semakin tinggi semakin bagus

### Status Pesan
- **Belum:** Pesan baru yang belum dibaca (badge merah)
- **Sudah:** Pesan yang sudah dibaca (badge hijau)
- Bisa diubah dengan klik tombol "Tandai Dibaca/Belum"

---

## Troubleshooting

### Masalah: Login gagal
**Solusi:** Cek username dan password apakah benar

### Masalah: Upload gambar gagal
**Solusi:**
- Cek folder `admin/uploads/` ada dan writable
- Cek ukuran file tidak melebihi 2MB
- Format file harus JPG, PNG, atau GIF

### Masalah: Session error
**Solusi:**
- Cek cookies browser sudah aktif
- Clear browser cache dan cookies
- Coba login ulang

### Masalah: Koneksi database gagal
**Solusi:**
- Cek MySQL sudah berjalan
- Cek konfigurasi database di `config.php`
- Cek database `db_jasa_dekorasi_pernikahan` sudah dibuat

---

## Fitur Keamanan

- ✅ Password di-hash dengan BCRYPT
- ✅ Session management untuk mencegah akses tanpa login
- ✅ Input validation untuk semua form
- ✅ HTML escape untuk output
- ✅ SQL prepared statements untuk mencegah SQL injection
- ✅ File validation untuk upload gambar
- ✅ Redirect otomatis jika ada akses tidak sah

---

## Update dan Maintenance

### Backup Database
Gunakan phpMyAdmin atau command line:
```bash
mysqldump -u root -p db_jasa_dekorasi_pernikahan > backup.sql
```

### Restore Database
```bash
mysql -u root -p db_jasa_dekorasi_pernikahan < backup.sql
```

---

**Selamat menggunakan Admin Panel!** 🎉

Jika ada pertanyaan atau masalah, silakan cek kembali dokumentasi ini atau hubungi developer.
