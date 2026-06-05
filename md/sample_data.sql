-- Sample Data untuk Testing Admin Panel
-- Import file ini setelah db.sql dan daftar akun admin

USE db_jasa_dekorasi_pernikahan;

-- ============================================
-- 1. Sample Data - Kategori
-- ============================================
INSERT INTO tb_kategori (nama_kategori, slug) VALUES
('Traditional', 'traditional'),
('Modern', 'modern'),
('Rustic', 'rustic'),
('Minimalist', 'minimalist');

-- ============================================
-- 2. Sample Data - Paket Dekorasi
-- ============================================
INSERT INTO tb_paket (id_kategori, nama_paket, harga, deskripsi, fitur, foto) VALUES
(1, 'Paket Traditional Deluxe', 8500000, 'Paket dekorasi pernikahan dengan konsep tradisional yang elegan dan penuh makna. Menggunakan elemen tradisional jawa dengan sentuhan modern.', 'Bunga Segar Pilihan\nKursi Dekorasi Cantik\nLampion Tradisional\nKain Premium\nDekorasi Pintu Masuk\nDekorasi Meja Inti\nPhotobooth Tradisional', NULL),
(2, 'Paket Modern Minimalis', 6500000, 'Paket dekorasi dengan konsep modern dan minimalis. Cocok untuk acara yang ingin tampil bersih, rapi, dan elegan dengan sentuhan kontemporer.', 'Bunga Modern Arrangement\nKursi Scandinavian\nLighting Minimalis\nKain Monokrom\nDekorasi Geometri\nMeja Styling Modern\nPhotobooth Minimalis', NULL),
(3, 'Paket Rustic Romantic', 7500000, 'Paket dekorasi rustic dengan sentuhan romantis. Menggunakan material alami, kayu, dan dekorasi yang memberikan kesan hangat dan intim.', 'Bunga Liar Arrangement\nKursi Wooden Rustic\nLampu String\nKain Linen Natural\nDekorasi Kayu Reclaimed\nMeja Vintage\nPhotobooth Rustic', NULL),
(4, 'Paket Minimalist Luxury', 5500000, 'Paket dekorasi minimalis dengan sentuhan luxury. Simple namun sophisticated, cocok untuk yang menginginkan kemewahan dalam kesederhanaan.', 'Bunga Premium White\nKursi Modern Design\nLighting Elegant\nKain Sutra Putih\nDekorasi Gold Accent\nMeja Minimalis Luxury\nPhotobooth Minimalis Luxury', NULL);

-- ============================================
-- 3. Sample Data - Testimoni
-- ============================================
INSERT INTO tb_testimoni (nama_klien, ulasan, bintang, foto_klien) VALUES
('Ayu Pertiwi', 'Dekorasinya sangat cantik dan sesuai dengan konsep yang kami mau. Tim sangat profesional dan responsif dalam komunikasi. Highly recommended!', 5, NULL),
('Rudi Hermansyah', 'Prosesnya lancar dari awal hingga akhir. Hasil dekorasinya melebihi ekspektasi kami. Terima kasih telah membuat hari spesial kami sempurna!', 5, NULL),
('Siti Nur Azizah', 'Pelayanannya sangat ramah dan tim kreatif. Dekorasi yang mereka buat sangat unik dan berkesan. Puas banget dengan hasilnya!', 5, NULL),
('Budi Santoso', 'Kualitas dekorasi bagus dan sesuai budget. Hanya saja proses finishing sedikit tergesa-gesa, tapi overall puas lah.', 4, NULL);

-- ============================================
-- 4. Sample Data - Portofolio
-- ============================================
INSERT INTO tb_portofolio (judul, deskripsi, foto, tanggal_event) VALUES
('Pernikahan Ayu & Rudi', 'Konsep traditional dengan sentuhan modern. Menggunakan warna emas dan cream dengan dekorasi bunga segar yang melimpah. Acara berjalan sangat meriah dan memorable.', NULL, '2024-01-15'),
('Pernikahan Siti & Ahmad', 'Konsep minimalis modern dengan palet warna putih dan hijau sage. Venue outdoor yang dipadukan dengan lighting cantik menciptakan suasana elegan dan romantic.', NULL, '2024-02-20'),
('Pernikahan Dewi & Hendra', 'Konsep rustic romantic dengan material kayu dan bunga liar. Intimate gathering dengan sentuhan natural yang sangat hangat dan personal.', NULL, '2024-03-10'),
('Pernikahan Nita & Kris', 'Konsep luxury minimalis dengan warna gold dan putih. Dekorasi sophisticated dengan attention to detail yang tinggi menciptakan kesan premium dan elegan.', NULL, '2024-04-05');

-- ============================================
-- 5. Sample Data - Pesan (Contact Form)
-- ============================================
INSERT INTO tb_pesan (nama_pengirim, email, no_whatsapp, pesan, status_baca, tanggal_kirim) VALUES
('Rina Handoko', 'rina@email.com', '081234567890', 'Halo, saya tertarik dengan paket Traditional Deluxe. Bisakah saya minta info lebih detail dan availability untuk bulan Mei?', 'belum', NOW() - INTERVAL '2 hour'),
('Arman Wijaya', 'arman@email.com', '082345678901', 'Saya sudah lihat portofolio kalian dan sangat terkesan. Berapa untuk paket custom dengan konsep rustic?', 'sudah', NOW() - INTERVAL '5 hour'),
('Lisa Pratama', 'lisa@email.com', '083456789012', 'Bisa di-customize warna sesuai preferensi kami?', 'belum', NOW() - INTERVAL '30 minute');

-- ============================================
-- Query untuk Verifikasi
-- ============================================

-- Cek semua kategori
-- SELECT * FROM tb_kategori;

-- Cek semua paket dengan kategorinya
-- SELECT p.*, k.nama_kategori FROM tb_paket p LEFT JOIN tb_kategori k ON p.id_kategori = k.id;

-- Cek testimoni dengan rating
-- SELECT * FROM tb_testimoni ORDER BY bintang DESC;

-- Cek portofolio
-- SELECT * FROM tb_portofolio ORDER BY tanggal_event DESC;

-- Cek pesan
-- SELECT * FROM tb_pesan ORDER BY tanggal_kirim DESC;

-- Cek statistik
-- SELECT 
--   (SELECT COUNT(*) FROM tb_kategori) as total_kategori,
--   (SELECT COUNT(*) FROM tb_paket) as total_paket,
--   (SELECT COUNT(*) FROM tb_gambar_produk) as total_gambar,
--   (SELECT COUNT(*) FROM tb_pesan WHERE status_baca = 'belum') as pesan_belum_dibaca;
