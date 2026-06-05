CREATE DATABASE IF NOT EXISTS db_jasa_dekorasi_pernikahan;
USE db_jasa_dekorasi_pernikahan;

-- 1. Tabel Admin
CREATE TABLE IF NOT EXISTS tb_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- 2. Tabel Kategori Dekorasi (e.g., Traditional, Modern, Rustic)
CREATE TABLE IF NOT EXISTS tb_kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

-- 3. Tabel Paket Dekorasi
CREATE TABLE IF NOT EXISTS tb_paket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT,
    nama_paket VARCHAR(150) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    deskripsi TEXT,
    fitur TEXT, -- Disimpan dalam format text/list (bisa dipisah koma atau newline)
    foto VARCHAR(255),
    FOREIGN KEY (id_kategori) REFERENCES tb_kategori(id) ON DELETE SET NULL
);

-- 4. Tabel Portofolio / Galeri
CREATE TABLE IF NOT EXISTS tb_portofolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255),
    tanggal_event DATE
);

-- 5. Tabel Testimoni
CREATE TABLE IF NOT EXISTS tb_testimoni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_klien VARCHAR(100) NOT NULL,
    ulasan TEXT NOT NULL,
    bintang INT DEFAULT 5,
    foto_klien VARCHAR(255) NULL
);

-- 6. Tabel Kontak / Pesan Masuk
CREATE TABLE IF NOT EXISTS tb_pesan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pengirim VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    no_whatsapp VARCHAR(20) NOT NULL,
    pesan TEXT NOT NULL,
    status_baca ENUM('belum', 'sudah') DEFAULT 'belum',
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);