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
    <style>
        .nav-link.active {
            @apply border-l-4 border-rose-600 bg-rose-50 text-gray-900;
        }
    </style>
</head>
<body class="bg-stone-50">
    <div class="flex h-screen">
        <!-- Include Sidebar (Reusable Component) -->
        <?php include 'includes/sidebar.php'; ?>

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
                // Load content berdasarkan page - redirect ke sidebar untuk CRUD
                $pages = [
                    'dashboard' => 'pages/dashboard.php',
                    'kategori' => 'sidebar/kategori.php',
                    'paket' => 'sidebar/paket.php',
                    'portofolio' => 'sidebar/portofolio.php',
                    'testimoni' => 'sidebar/testimoni.php',
                    'pesan' => 'sidebar/pesan.php'
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
