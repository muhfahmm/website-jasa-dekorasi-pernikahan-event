<?php
/**
 * Dashboard Overview
 * Halaman overview dashboard dengan statistik
 */

require_once '../../config.php';
?>

<!-- Welcome Card -->
<div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-lg shadow-sm p-8 border border-rose-100 mb-8">
    <h2 class="text-2xl font-serif font-bold text-gray-800 mb-2">Selamat Datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
    <p class="text-gray-600">Kelola seluruh konten website jasa dekorasi pernikahan Anda dari sini.</p>
</div>

<!-- Statistics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Kategori -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-rose-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Kategori</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) as total FROM tb_kategori");
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </p>
            </div>
            <svg class="w-12 h-12 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
        </div>
    </div>

    <!-- Total Paket -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Paket</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) as total FROM tb_paket");
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </p>
            </div>
            <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m0-10v10l8-4M3 12a9 9 0 0118 0m0 0a9 9 0 01-18 0m0 0a8.999 8.999 0 0118 0"></path>
            </svg>
        </div>
    </div>

    <!-- Total Gambar -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Gambar</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) as total FROM tb_gambar_produk");
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </p>
            </div>
            <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>

    <!-- Pesan Belum Dibaca -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pesan Belum Dibaca</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) as total FROM tb_pesan WHERE status_baca = 'belum'");
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </p>
            </div>
            <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="?page=kategori" class="bg-gradient-to-r from-rose-50 to-pink-50 hover:from-rose-100 hover:to-pink-100 rounded-lg p-4 border border-rose-200 transition-all">
            <p class="font-semibold text-gray-800">Tambah Kategori</p>
            <p class="text-sm text-gray-600 mt-1">Buat kategori dekorasi baru</p>
        </a>
        <a href="?page=paket" class="bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 rounded-lg p-4 border border-green-200 transition-all">
            <p class="font-semibold text-gray-800">Tambah Paket</p>
            <p class="text-sm text-gray-600 mt-1">Buat paket dekorasi baru</p>
        </a>
        <a href="?page=gambar" class="bg-gradient-to-r from-blue-50 to-cyan-50 hover:from-blue-100 hover:to-cyan-100 rounded-lg p-4 border border-blue-200 transition-all">
            <p class="font-semibold text-gray-800">Upload Gambar</p>
            <p class="text-sm text-gray-600 mt-1">Tambahkan gambar produk baru</p>
        </a>
        <a href="?page=portofolio" class="bg-gradient-to-r from-purple-50 to-indigo-50 hover:from-purple-100 hover:to-indigo-100 rounded-lg p-4 border border-purple-200 transition-all">
            <p class="font-semibold text-gray-800">Tambah Portofolio</p>
            <p class="text-sm text-gray-600 mt-1">Tambahkan karya ke galeri</p>
        </a>
        <a href="?page=testimoni" class="bg-gradient-to-r from-yellow-50 to-amber-50 hover:from-yellow-100 hover:to-amber-100 rounded-lg p-4 border border-yellow-200 transition-all">
            <p class="font-semibold text-gray-800">Kelola Testimoni</p>
            <p class="text-sm text-gray-600 mt-1">Atur ulasan klien</p>
        </a>
        <a href="?page=pesan" class="bg-gradient-to-r from-orange-50 to-red-50 hover:from-orange-100 hover:to-red-100 rounded-lg p-4 border border-orange-200 transition-all">
            <p class="font-semibold text-gray-800">Lihat Pesan</p>
            <p class="text-sm text-gray-600 mt-1">Cek pesan masuk dari pelanggan</p>
        </a>
    </div>
</div>
