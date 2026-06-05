# Admin Panel - Quick Start

## Akses Admin Panel

**URL Login:**
```
http://localhost/project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/login.php
```

---

## Langkah Pertama (Setup Awal)

### 1. Pastikan Database Sudah Ada
Buka phpMyAdmin atau MySQL command line:
```sql
-- Jalankan file db.sql dari folder md/
SOURCE /path/to/md/db.sql;
```

### 2. Buat Akun Admin Pertama
1. Buka URL login di atas
2. Klik link "Daftar di sini"
3. Isi form registrasi dengan:
   - Username: `admin` (atau yang lain)
   - Password: minimal 6 karakter
   - Konfirmasi password
4. Klik "Daftar"

### 3. Login ke Dashboard
1. Kembali ke login page
2. Masukkan username dan password
3. Klik "Masuk"
4. Anda akan diarahkan ke dashboard

---

## Struktur Menu Admin

```
Dashboard
├── Statistik
│   ├── Total Kategori
│   ├── Total Paket
│   ├── Total Gambar
│   └── Pesan Belum Dibaca
└── Aksi Cepat
    ├── Tambah Kategori
    ├── Tambah Paket
    ├── Upload Gambar
    ├── Tambah Portofolio
    ├── Kelola Testimoni
    └── Lihat Pesan

Menu Sidebar:
├── Dashboard
├── Kategori
├── Paket Dekorasi
├── Gambar Produk
├── Portofolio
├── Testimoni
└── Pesan Masuk
```

---

## Workflow Dasar

### Menambah Paket Dekorasi

**Tahap 1: Siapkan Kategori**
1. Pergi ke Menu → Kategori
2. Tambah kategori (misal: "Modern", "Traditional", "Rustic")
3. Isi nama kategori dan slug

**Tahap 2: Buat Paket**
1. Pergi ke Menu → Paket Dekorasi
2. Isi form:
   - Kategori: Pilih kategori dari Tahap 1
   - Nama Paket: "Paket Premium Modern"
   - Harga: 5000000
   - Deskripsi: Jelaskan detail paket
   - Fitur: Cantumkan fitur (pisahkan dengan Enter)
   - Foto: Upload gambar paket
3. Klik "Tambah Paket"

**Tahap 3: Upload Gambar Produk**
1. Pergi ke Menu → Gambar Produk
2. Isi form:
   - Paket: Pilih paket dari Tahap 2
   - Nama Gambar: "Tampak Depan" atau "Detail Bunga"
   - Urutan: 1, 2, 3, dll
   - File Gambar: Upload file gambar
3. Klik "Upload Gambar"
4. Ulangi untuk multiple gambar per paket

---

## Upload Folder Setup

**Pastikan folder ini ada dan writable:**
```
admin/uploads/
```

**Windows (di command prompt):**
```cmd
mkdir admin\uploads
icacls admin\uploads /grant %username%:(OI)(CI)F
```

**Linux/Mac:**
```bash
mkdir -p admin/uploads
chmod 755 admin/uploads
```

---

## Login Session

- **Session timeout:** Default PHP (biasanya 24 menit)
- **Session storage:** PHP default (file atau database)
- **Jika sudah login:** Tidak bisa kembali ke login/register
- **Logout:** Klik tombol "Logout" di sidebar bawah

---

## File Penting

| File | Fungsi |
|------|--------|
| `config.php` | Konfigurasi database |
| `auth/session.php` | Session management |
| `auth/login.php` | Halaman login |
| `auth/register.php` | Halaman registrasi |
| `auth/logout.php` | Script logout |
| `dashboard.php` | Main dashboard |
| `pages/*.php` | Halaman menu |

---

## Database Info

**Database Name:** `db_jasa_dekorasi_pernikahan`

**Admin Table:**
```sql
SELECT * FROM tb_admin;  -- Lihat semua admin
```

---

## Troubleshooting

### Lupa Password?
Saat ini tidak ada fitur reset password. Hubungi database admin untuk delete akun lama dan buat akun baru.

### Folder uploads tidak writable?
```php
// Test di PHP
if (is_writable('../uploads')) {
    echo "Writable";
} else {
    echo "Not writable";
}
```

### Gambar tidak terupload?
- Cek ukuran file (max 2MB)
- Cek format (hanya JPG, PNG, GIF)
- Cek folder `admin/uploads/` exists dan writable

---

## Selanjutnya

1. **Baca dokumentasi lengkap:** `ADMIN_PANEL_GUIDE.md`
2. **Mulai kelola konten:** Upload kategori, paket, gambar, dll
3. **Customize design:** Edit warna di CSS classes (Rose, Green, Blue, dll)
4. **Setup homepage:** Buat halaman depan yang menggunakan data dari database

---

**Happy managing!** 🎉
