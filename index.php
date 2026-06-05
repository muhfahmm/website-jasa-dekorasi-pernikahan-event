<?php
/**
 * Home Page - User Website
 * Menampilkan informasi jasa dekorasi pernikahan dengan data dari database
 */

require_once 'config.php';

// Get kategori dari database
$kategori = [];
$result = $conn->query("SELECT id, nama_kategori, slug FROM tb_kategori");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kategori[] = $row;
    }
}

// Get paket dari database
$paket = [];
$result = $conn->query("SELECT id, id_kategori, nama_paket, harga, deskripsi, foto FROM tb_paket LIMIT 6");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $paket[] = $row;
    }
}

// Get portofolio dari database
$portofolio = [];
$result = $conn->query("SELECT id, judul, deskripsi, foto, tanggal_event FROM tb_portofolio LIMIT 6");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $portofolio[] = $row;
    }
}

// Get testimoni dari database
$testimoni = [];
$result = $conn->query("SELECT id, nama_klien, ulasan, bintang, foto_klien FROM tb_testimoni LIMIT 6");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimoni[] = $row;
    }
}

// Handle form submit untuk pesan
$pesan_berhasil = '';
$pesan_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_whatsapp = trim($_POST['no_whatsapp'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    // Validasi
    if (empty($nama) || empty($email) || empty($no_whatsapp) || empty($pesan)) {
        $pesan_error = 'Semua field harus diisi!';
    } else {
        // Insert ke database
        $stmt = $conn->prepare("INSERT INTO tb_pesan (nama_pengirim, email, no_whatsapp, pesan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $email, $no_whatsapp, $pesan);
        
        if ($stmt->execute()) {
            $pesan_berhasil = 'Pesan berhasil dikirim! Tim kami akan segera menghubungi Anda.';
            $nama = $email = $no_whatsapp = $pesan = '';
        } else {
            $pesan_error = 'Gagal mengirim pesan. Silakan coba lagi.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jasa Dekorasi Pernikahan - Wujudkan Pernikahan Impian Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html {
            scroll-behavior: smooth;
        }
        .star-rating {
            color: #fbbf24;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <nav class="fixed w-full bg-white shadow-md z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-heart text-rose-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-800">Dekorasi Pernikahan</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-rose-600 transition font-medium">Home</a>
                    <a href="#kategori" class="text-gray-700 hover:text-rose-600 transition font-medium">Kategori</a>
                    <a href="#paket" class="text-gray-700 hover:text-rose-600 transition font-medium">Paket</a>
                    <a href="#portofolio" class="text-gray-700 hover:text-rose-600 transition font-medium">Portofolio</a>
                    <a href="#testimoni" class="text-gray-700 hover:text-rose-600 transition font-medium">Testimoni</a>
                    <a href="#kontak" class="text-gray-700 hover:text-rose-600 transition font-medium">Kontak</a>
                </div>
                <a href="admin/" class="bg-rose-600 text-white px-4 py-2 rounded-lg hover:bg-rose-700 transition text-sm font-medium">
                    Login Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="bg-gradient-to-r from-rose-500 to-pink-500 text-white pt-32 pb-20 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Wujudkan Pernikahan Impian Anda</h1>
        <p class="text-lg md:text-xl mb-8 opacity-90">Dekorasi pernikahan profesional dengan desain terkini dan tim berpengalaman</p>
        <a href="#paket" class="inline-block bg-white text-rose-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
            Lihat Paket Kami
        </a>
    </section>

    <!-- Kategori Section -->
    <section id="kategori" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Kategori Dekorasi</h2>
            <p class="text-center text-gray-600 mb-12">Pilih kategori dekorasi sesuai dengan tema impian Anda</p>
            
            <?php if (count($kategori) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($kategori as $kat): ?>
                        <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            <div class="text-5xl mb-4">
                                <?php
                                    $icons = [
                                        'traditional' => '<i class="fas fa-crown text-rose-600"></i>',
                                        'modern' => '<i class="fas fa-sparkles text-blue-600"></i>',
                                        'rustic' => '<i class="fas fa-leaf text-green-600"></i>'
                                    ];
                                    echo $icons[$kat['slug']] ?? '<i class="fas fa-heart text-rose-600"></i>';
                                ?>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-gray-800"><?php echo htmlspecialchars($kat['nama_kategori']); ?></h3>
                            <p class="text-gray-600 mb-4">Dekorasi dengan konsep <?php echo htmlspecialchars(strtolower($kat['nama_kategori'])); ?> yang elegan dan memukau</p>
                            <a href="#paket" class="text-rose-600 font-bold hover:text-rose-700 transition">
                                Lihat Paket →
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg text-center">
                    <p class="text-yellow-700">Kategori belum tersedia. Silakan hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Paket Section -->
    <section id="paket" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Paket Dekorasi</h2>
            <p class="text-center text-gray-600 mb-12">Pilih paket yang sesuai dengan budget dan kebutuhan Anda</p>
            
            <?php if (count($paket) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php $index = 0; foreach ($paket as $pkt): $index++; 
                        $highlight = ($index === 2); // Highlight paket kedua
                    ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition <?php echo $highlight ? 'transform scale-105 border-2 border-rose-600' : ''; ?>">
                            <?php if ($pkt['foto']): ?>
                                <div class="h-40 bg-gradient-to-br from-rose-300 to-pink-300 overflow-hidden">
                                    <img src="admin/sidebar/<?php echo htmlspecialchars($pkt['foto']); ?>" alt="<?php echo htmlspecialchars($pkt['nama_paket']); ?>" class="w-full h-full object-cover">
                                </div>
                            <?php else: ?>
                                <div class="h-40 bg-gradient-to-br from-rose-300 to-pink-300 flex items-center justify-center">
                                    <i class="fas fa-image text-white text-5xl opacity-30"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($highlight): ?>
                                <div class="bg-rose-600 text-white text-center py-2 font-bold">
                                    ⭐ REKOMENDASI TERPOPULER
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <h3 class="text-2xl font-bold mb-2 text-gray-800"><?php echo htmlspecialchars($pkt['nama_paket']); ?></h3>
                                <p class="text-gray-600 mb-4 text-sm"><?php echo htmlspecialchars(substr($pkt['deskripsi'] ?? '', 0, 100)); ?>...</p>
                                <div class="text-3xl font-bold text-rose-600 mb-6">
                                    Rp <?php echo number_format($pkt['harga'], 0, ',', '.'); ?>
                                </div>
                                <a href="#kontak" class="block w-full bg-rose-600 text-white py-3 rounded-lg hover:bg-rose-700 transition font-bold text-center">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg text-center">
                    <p class="text-yellow-700">Paket belum tersedia. Silakan hubungi kami untuk konsultasi gratis.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Portofolio Section -->
    <section id="portofolio" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Portofolio Kami</h2>
            <p class="text-center text-gray-600 mb-12">Hasil karya dekorasi pernikahan yang telah kami tangani</p>
            
            <?php if (count($portofolio) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($portofolio as $prf): ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition group cursor-pointer">
                            <?php if ($prf['foto']): ?>
                                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-rose-300 to-pink-300">
                                    <img src="admin/sidebar/<?php echo htmlspecialchars($prf['foto']); ?>" alt="<?php echo htmlspecialchars($prf['judul']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                </div>
                            <?php else: ?>
                                <div class="h-48 bg-gradient-to-br from-rose-300 to-pink-300 flex items-center justify-center">
                                    <i class="fas fa-image text-white text-5xl opacity-30"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-4">
                                <h3 class="text-lg font-bold mb-2 text-gray-800"><?php echo htmlspecialchars($prf['judul']); ?></h3>
                                <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars(substr($prf['deskripsi'] ?? '', 0, 80)); ?>...</p>
                                <?php if ($prf['tanggal_event']): ?>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <?php echo date('d M Y', strtotime($prf['tanggal_event'])); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg text-center">
                    <p class="text-yellow-700">Portofolio sedang diperbarui. Silakan kunjungi kembali nanti.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section id="testimoni" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Testimoni Klien</h2>
            <p class="text-center text-gray-600 mb-12">Kepuasan klien adalah prioritas utama kami</p>
            
            <?php if (count($testimoni) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($testimoni as $tes): ?>
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition border-l-4 border-rose-600">
                            <div class="flex items-center mb-4">
                                <?php if ($tes['foto_klien']): ?>
                                    <img src="admin/sidebar/<?php echo htmlspecialchars($tes['foto_klien']); ?>" alt="<?php echo htmlspecialchars($tes['nama_klien']); ?>" class="w-12 h-12 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gradient-to-r from-rose-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold">
                                        <?php echo substr($tes['nama_klien'], 0, 1); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-bold text-gray-800"><?php echo htmlspecialchars($tes['nama_klien']); ?></h3>
                                    <div class="star-rating text-sm">
                                        <?php 
                                        $bintang = $tes['bintang'] ?? 5;
                                        for ($i = 0; $i < $bintang; $i++) {
                                            echo '★';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"<?php echo htmlspecialchars(substr($tes['ulasan'] ?? '', 0, 120)); ?>..."</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg text-center">
                    <p class="text-yellow-700">Testimoni sedang diperbarui. Jadilah klien kami pertama!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Hubungi Kami</h2>
            <p class="text-center text-gray-600 mb-12">Konsultasi gratis untuk pernikahan impian Anda</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Info Kontak -->
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Informasi Kontak</h3>
                    
                    <div class="mb-6 flex items-start">
                        <div class="text-rose-600 text-xl mr-4 mt-1">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Telepon</p>
                            <p class="text-gray-600">+62 812-3456-7890</p>
                        </div>
                    </div>
                    
                    <div class="mb-6 flex items-start">
                        <div class="text-rose-600 text-xl mr-4 mt-1">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Email</p>
                            <p class="text-gray-600">info@dekorasiperfect.com</p>
                        </div>
                    </div>
                    
                    <div class="mb-6 flex items-start">
                        <div class="text-rose-600 text-xl mr-4 mt-1">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Alamat</p>
                            <p class="text-gray-600">Jl. Contoh No. 123, Jakarta</p>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <p class="font-bold text-gray-800 mb-4">Ikuti Kami</p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 bg-rose-600 text-white rounded-full flex items-center justify-center hover:bg-rose-700 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-rose-600 text-white rounded-full flex items-center justify-center hover:bg-rose-700 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-rose-600 text-white rounded-full flex items-center justify-center hover:bg-rose-700 transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-rose-600 text-white rounded-full flex items-center justify-center hover:bg-rose-700 transition">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Form Kontak -->
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Kirim Pesan</h3>
                    
                    <?php if ($pesan_berhasil): ?>
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i><?php echo $pesan_berhasil; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($pesan_error): ?>
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-700"><i class="fas fa-exclamation-circle mr-2"></i><?php echo $pesan_error; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500" placeholder="Nama Anda" value="<?php echo htmlspecialchars($nama ?? ''); ?>" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500" placeholder="Email Anda" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">No. WhatsApp</label>
                            <input type="tel" name="no_whatsapp" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500" placeholder="+62 812-3456-7890" value="<?php echo htmlspecialchars($no_whatsapp ?? ''); ?>" required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Pesan</label>
                            <textarea name="pesan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500" rows="4" placeholder="Pesan Anda" required><?php echo htmlspecialchars($pesan ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-rose-600 text-white py-3 rounded-lg hover:bg-rose-700 transition font-bold">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Jasa Dekorasi Pernikahan</h3>
                    <p class="text-gray-400">Mewujudkan pernikahan impian Anda dengan dekorasi berkualitas tinggi dan profesional.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Menu Cepat</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#home" class="hover:text-white transition">Home</a></li>
                        <li><a href="#paket" class="hover:text-white transition">Paket</a></li>
                        <li><a href="#portofolio" class="hover:text-white transition">Portofolio</a></li>
                        <li><a href="#kontak" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Hubungi Kami</h3>
                    <p class="text-gray-400 mb-2"><i class="fas fa-phone mr-2"></i>+62 812-3456-7890</p>
                    <p class="text-gray-400"><i class="fas fa-envelope mr-2"></i>info@dekorasiperfect.com</p>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Jasa Dekorasi Pernikahan. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll behavior already handled by CSS
        // Add any additional JavaScript as needed
    </script>
</body>
</html>
