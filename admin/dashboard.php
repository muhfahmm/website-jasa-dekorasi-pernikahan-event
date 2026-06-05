<?php
/**
 * Admin Dashboard
 * Halaman utama admin dengan menu dan data management
 */

require_once '../config.php';
require_once 'auth/session.php';

// Cek session, redirect jika belum login
requireLogin();

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
$current_page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Smooth scroll untuk navigasi
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
    <style>
        .nav-link.active {
            @apply border-l-4 border-rose-600 bg-rose-50;
        }
    </style>
</head>
<body class="bg-stone-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col">
            <!-- Logo/Header -->
            <div class="p-6 border-b border-slate-700">
                <h1 class="text-2xl font-serif font-bold">Admin</h1>
                <p class="text-slate-400 text-sm mt-1">Jasa Dekorasi</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="?page=dashboard" class="nav-link active flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l4-4m0 0l4 4m-4-4V5"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Kategori -->
                <a href="?page=kategori" class="nav-link flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    Kategori
                </a>

                <!-- Paket -->
                <a href="?page=paket" class="nav-link flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m0-10v10l8-4M3 12a9 9 0 0118 0m0 0a9 9 0 01-18 0m0 0a8.999 8.999 0 0118 0"></path>
                    </svg>
                    Paket Dekorasi
                </a>

                <!-- Gambar Produk -->
                <a href="?page=gambar" class="nav-link flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Gambar Produk
                </a>

                <!-- Portofolio -->
                <a href="?page=portofolio" class="nav-link flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    Portofolio
                </a>

                <!-- Testimoni -->
                <a href="?page=testimoni" class="nav-link flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    Testimoni
                </a>

                <!-- Pesan Masuk -->
                <a href="?page=pesan" class="nav-link flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Pesan Masuk
                </a>
            </nav>

            <!-- Divider -->
            <div class="border-t border-slate-700 px-4 py-4 space-y-2">
                <!-- User Info -->
                <div class="px-4 py-3 bg-slate-800 rounded-lg">
                    <p class="text-xs text-slate-400">Login sebagai</p>
                    <p class="text-sm font-medium text-white truncate"><?php echo htmlspecialchars($admin_username); ?></p>
                </div>

                <!-- Logout Button -->
                <a href="auth/logout.php" class="flex items-center px-4 py-3 rounded-lg text-slate-100 hover:bg-red-900 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-serif font-bold text-gray-800">
                            <?php 
                            $titles = [
                                'dashboard' => 'Dashboard',
                                'kategori' => 'Kelola Kategori',
                                'paket' => 'Kelola Paket Dekorasi',
                                'gambar' => 'Kelola Gambar Produk',
                                'portofolio' => 'Kelola Portofolio',
                                'testimoni' => 'Kelola Testimoni',
                                'pesan' => 'Pesan Masuk'
                            ];
                            echo $titles[$current_page] ?? 'Dashboard';
                            ?>
                        </h1>
                        <p class="text-gray-600 text-sm mt-1">Kelola konten website Anda di sini</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">
                            <?php echo date('l, d F Y', time()); ?>
                        </p>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-8">
                <?php
                // Load content berdasarkan page
                $pages = [
                    'dashboard' => 'pages/dashboard.php',
                    'kategori' => 'pages/kategori.php',
                    'paket' => 'pages/paket.php',
                    'gambar' => 'pages/gambar.php',
                    'portofolio' => 'pages/portofolio.php',
                    'testimoni' => 'pages/testimoni.php',
                    'pesan' => 'pages/pesan.php'
                ];

                $page_file = $pages[$current_page] ?? 'pages/dashboard.php';
                
                if (file_exists($page_file)) {
                    include $page_file;
                } else {
                    include 'pages/dashboard.php';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>
