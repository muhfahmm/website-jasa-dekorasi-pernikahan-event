<?php
/**
 * Kategori Management
 * Halaman untuk mengelola kategori dekorasi
 */

require_once __DIR__ . '/../../config.php';

$error = '';
$success = '';

// Proses tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    if (empty($nama_kategori)) {
        $error = 'Nama kategori tidak boleh kosong';
    } elseif (empty($slug)) {
        $error = 'Slug tidak boleh kosong';
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_kategori (nama_kategori, slug) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama_kategori, $slug);

        if ($stmt->execute()) {
            $success = 'Kategori berhasil ditambahkan';
            $nama_kategori = '';
            $slug = '';
        } else {
            if ($conn->errno === 1062) {
                $error = 'Slug sudah digunakan';
            } else {
                $error = 'Gagal menambahkan kategori';
            }
        }
        $stmt->close();
    }
}

// Proses hapus kategori
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM tb_kategori WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Kategori berhasil dihapus';
    } else {
        $error = 'Gagal menghapus kategori';
    }
    $stmt->close();
}

// Get all kategori
$kategori_list = $conn->query("SELECT * FROM tb_kategori ORDER BY id DESC");
?>

<div class="space-y-6">
    <!-- Add Kategori Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Tambah Kategori Baru</h3>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700 text-sm">⚠️ <?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 text-sm">✓ <?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="add">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input 
                        type="text" 
                        name="nama_kategori" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="Contoh: Traditional, Modern, Rustic"
                        value="<?php echo htmlspecialchars($nama_kategori ?? ''); ?>"
                    >
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input 
                        type="text" 
                        name="slug" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="Contoh: traditional, modern, rustic"
                        value="<?php echo htmlspecialchars($slug ?? ''); ?>"
                    >
                    <p class="text-xs text-gray-500 mt-1">Gunakan huruf kecil dan tanda hubung (-)</p>
                </div>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition-colors"
            >
                Tambah Kategori
            </button>
        </form>
    </div>

    <!-- Kategori List -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Kategori</h3>

        <?php if ($kategori_list->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-6 py-3 text-left font-medium text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Nama Kategori</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Slug</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while ($row = $kategori_list->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800"><?php echo $row['id']; ?></td>
                                <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                                <td class="px-6 py-4 text-gray-600 font-mono text-xs"><?php echo htmlspecialchars($row['slug']); ?></td>
                                <td class="px-6 py-4">
                                    <a href="?page=kategori&delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">Belum ada kategori. Silakan tambahkan kategori baru.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
