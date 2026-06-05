<?php
/**
 * Login Page
 * Halaman login admin dengan validasi
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

    // Validasi input
    if (empty($username)) {
        $error = 'Username tidak boleh kosong';
    } elseif (empty($password)) {
        $error = 'Password tidak boleh kosong';
    } else {
        // Query database
        $stmt = $conn->prepare("SELECT id, username, password FROM tb_admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                setLoginSession($user['id'], $user['username']);
                header('Location: ../dashboard.php');
                exit();
            } else {
                $error = 'Password salah';
            }
        } else {
            $error = 'Username tidak ditemukan';
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
    <title>Login - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Card Login -->
            <div class="bg-white rounded-lg shadow-sm p-8 border border-rose-100">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-2">Admin Panel</h1>
                    <p class="text-gray-600 text-sm">Jasa Dekorasi Pernikahan</p>
                </div>

                <!-- Error Message -->
                <?php if ($error): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-700 text-sm">⚠️ <?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
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
                            placeholder="Masukkan username"
                            value="<?php echo htmlspecialchars($username ?? ''); ?>"
                            required
                        >
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

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-rose-600 text-white font-medium py-2.5 rounded-lg hover:bg-rose-700 transition-colors duration-200"
                    >
                        Masuk
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center my-6">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-gray-500 text-sm">atau</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Belum punya akun? 
                        <a href="register.php" class="text-rose-600 hover:text-rose-700 font-medium">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-6 text-center text-gray-500 text-xs">
                <p>© 2024 Jasa Dekorasi Pernikahan. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>
