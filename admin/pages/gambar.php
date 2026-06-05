<?php
/**
 * Gambar Produk Management
 * Halaman untuk mengelola gambar produk
 */

require_once '../../config.php';

$error = '';
$success = '';

// Proses tambah gambar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $id_paket = intval($_POST['id_paket'] ?? 0);
    $nama_gambar = trim($_POST['nama_gambar'] ?? '');
    $urutan = intval($_POST['urutan'] ?? 1);

    // Validasi
    if ($id_paket === 0) {
        $error = 'Pilih paket terlebih dahulu';
    } elseif (empty($nama_gambar)) {
        $error = 'Nama gambar tidak boleh kosong';
    } elseif (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Pilih file gambar terlebih dahulu';
    } else {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF';
        } else {
            $new_filename = 'produk_' . time() . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $stmt = $conn->prepare("INSERT INTO tb_gambar_produk (id_paket, nama_gambar, path_gambar, urutan) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("issi", $id_paket, $nama_gambar, $new_filename, $urutan);

                if ($stmt->execute()) {
                    $success = 'Gambar berhasil ditambahkan';
                    $id_paket = '';
                    $nama_gambar = '';
                    $urutan = 1;
                } else {
                    $error = 'Gagal menambahkan gambar ke database';
                    unlink($upload_path);
                }
                $stmt->close();
            } else {
                $error = 'Gagal upload gambar';
            }
        }
    }
}

// Proses hapus gambar
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get file path untuk dihapus
    $stmt = $conn->prepare("SELECT path_gambar FROM tb_gambar_produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = '../uploads/' . $row['path_gambar'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM tb_gambar_produk WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Gambar berhasil dihapus';
    } else {
        $error = 'Gagal menghapus gambar';
    }
    $stmt->close();
}

// Get all paket
$paket_result = $conn->query("SELECT id, nama_paket FROM tb_paket ORDER BY nama_paket");

// Get all gambar
$gambar_list = $conn->query("
    SELECT g.*, p.nama_paket 
    FROM tb_gambar_produk g 
    LEFT JOIN tb_paket p ON g.id_paket = p.id 
    ORDER BY g.id DESC
");
?>

<div class="space-y-6">
    <!-- Add Gambar Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Upload Gambar Produk</h3>

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

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="add">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Paket -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Paket Dekorasi</label>
                    <select name="id_paket" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <option value="">-- Pilih Paket --</option>
                        <?php 
                        $paket_result->data_seek(0);
                        while ($paket = $paket_result->fetch_assoc()): ?>
                            <option value="<?php echo $paket['id']; ?>"><?php echo htmlspecialchars($paket['nama_paket']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Nama Gambar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Gambar</label>
                    <input 
                        type="text" 
                        name="nama_gambar" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="Contoh: Tampak Depan, Detail Bunga"
                        value="<?php echo htmlspecialchars($nama_gambar ?? ''); ?>"
                    >
                </div>

                <!-- Urutan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                    <input 
                        type="number" 
                        name="urutan" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="1"
                        value="<?php echo htmlspecialchars($urutan ?? 1); ?>"
                        min="1"
                    >
                </div>

                <!-- File Gambar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Gambar</label>
                    <input 
                        type="file" 
                        name="gambar" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        accept="image/*"
                    >
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, atau GIF (max 2MB)</p>
                </div>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
            >
                Upload Gambar
            </button>
        </form>
    </div>

    <!-- Gambar List -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Gambar Produk</h3>

        <?php if ($gambar_list->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Nama Gambar</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Paket</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">File</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Urutan</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Diupload</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while ($row = $gambar_list->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($row['nama_gambar']); ?></td>
                                <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($row['nama_paket'] ?? '-'); ?></td>
                                <td class="px-6 py-4 text-gray-600 text-xs font-mono"><?php echo htmlspecialchars($row['path_gambar']); ?></td>
                                <td class="px-6 py-4 text-gray-800"><?php echo $row['urutan']; ?></td>
                                <td class="px-6 py-4 text-gray-600 text-xs"><?php echo date('d/m/Y H:i', strtotime($row['tanggal_upload'])); ?></td>
                                <td class="px-6 py-4">
                                    <a href="?page=gambar&delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('Yakin ingin menghapus?')">
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
                <p class="text-gray-500">Belum ada gambar. Silakan upload gambar baru.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
