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
                <?php include 'sidebar/dashboard.php'; ?>

                <!-- Kategori -->
                <?php include 'sidebar/kategori.php'; ?>
                
                <!-- Paket -->
                <?php include 'sidebar/paket.php'; ?>

                <!-- Gambar Produk -->
                <?php include 'sidebar/gambar.php'; ?>

                <!-- Portofolio -->
                <?php include 'sidebar/portofolio.php'; ?>

                <!-- Testimoni -->
                <?php include 'sidebar/testimoni.php'; ?>

                <!-- Pesan Masuk -->
                <?php include 'sidebar/pesan.php'; ?>
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
