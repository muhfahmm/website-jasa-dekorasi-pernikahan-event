<?php
/**
 * Register Page
 * Halaman registrasi admin dengan validasi dan password hashing
 */

require_once '../../config.php';
require_once 'session.php';

// Jika sudah login, redirect ke dashboard
redirectIfLoggedIn();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi input
    if (empty($username)) {
        $error = 'Username tidak boleh kosong';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter';
    } elseif (strlen($username) > 50) {
        $error = 'Username maksimal 50 karakter';
    } elseif (empty($password)) {
        $error = 'Password tidak boleh kosong';
    } elseif ($password !== $confirm_password) {
        $error = 'Password tidak cocok';
    } else {
        // Cek username sudah ada atau belum
        $stmt = $conn->prepare("SELECT id FROM tb_admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username sudah terdaftar';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert ke database
            $stmt = $conn->prepare("INSERT INTO tb_admin (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login dengan akun Anda.';
                // Clear form
                $username = '';
                $password = '';
                $confirm_password = '';
            } else {
                $error = 'Terjadi kesalahan saat registrasi. Silakan coba lagi.';
            }
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
    <title>Daftar - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Card Register -->
            <div class="bg-white rounded-lg shadow-sm p-8 border border-rose-100">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-2">Daftar Admin</h1>
                    <p class="text-gray-600 text-sm">Jasa Dekorasi Pernikahan</p>
                </div>

                <!-- Success Message -->
                <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-700 text-sm">✓ <?php echo htmlspecialchars($success); ?></p>
                    </div>
                    <div class="text-center mb-6">
                        <a href="login.php" class="text-rose-600 hover:text-rose-700 font-medium text-sm">
                            Kembali ke login
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Error Message -->
                    <?php if ($error): ?>
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-700 text-sm">⚠️ <?php echo htmlspecialchars($error); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Register Form -->
                    <form method="POST" class="space-y-5">
                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="Minimal 3 karakter"
                                value="<?php echo htmlspecialchars($username ?? ''); ?>"
                                required
                            >
                            <p class="text-gray-500 text-xs mt-1">3 - 50 karakter</p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="Masukkan password"
                                required
                            >
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="Ulangi password"
                                required
                            >
                        </div>

                        <!-- Register Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-green-600 text-white font-medium py-2.5 rounded-lg hover:bg-green-700 transition-colors duration-200"
                        >
                            Daftar
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center my-6">
                        <div class="flex-1 border-t border-gray-300"></div>
                        <span class="px-4 text-gray-500 text-sm">atau</span>
                        <div class="flex-1 border-t border-gray-300"></div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-600 text-sm">
                            Sudah punya akun? 
                            <a href="login.php" class="text-rose-600 hover:text-rose-700 font-medium">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Info -->
            <div class="mt-6 text-center text-gray-500 text-xs">
                <p>© 2024 Jasa Dekorasi Pernikahan. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>
