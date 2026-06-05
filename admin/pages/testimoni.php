<?php
/**
 * Testimoni Management
 * Halaman untuk mengelola testimoni klien
 */

require_once '../../config.php';

$error = '';
$success = '';

// Proses tambah testimoni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nama_klien = trim($_POST['nama_klien'] ?? '');
    $ulasan = trim($_POST['ulasan'] ?? '');
    $bintang = intval($_POST['bintang'] ?? 5);

    // Validasi
    if (empty($nama_klien)) {
        $error = 'Nama klien tidak boleh kosong';
    } elseif (empty($ulasan)) {
        $error = 'Ulasan tidak boleh kosong';
    } elseif ($bintang < 1 || $bintang > 5) {
        $error = 'Rating bintang harus antara 1-5';
    } else {
        $foto_klien = null;
        
        // Handle file upload
        if (isset($_FILES['foto_klien']) && $_FILES['foto_klien']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['foto_klien']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF';
            } else {
                $new_filename = 'testimoni_' . time() . '.' . $ext;
                $upload_path = '../uploads/' . $new_filename;

                if (move_uploaded_file($_FILES['foto_klien']['tmp_name'], $upload_path)) {
                    $foto_klien = $new_filename;
                } else {
                    $error = 'Gagal upload foto klien';
                }
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO tb_testimoni (nama_klien, ulasan, bintang, foto_klien) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $nama_klien, $ulasan, $bintang, $foto_klien);

            if ($stmt->execute()) {
                $success = 'Testimoni berhasil ditambahkan';
                $nama_klien = '';
                $ulasan = '';
                $bintang = 5;
            } else {
                $error = 'Gagal menambahkan testimoni';
                if ($foto_klien) {
                    unlink('../uploads/' . $foto_klien);
                }
            }
            $stmt->close();
        }
    }
}

// Proses hapus testimoni
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get foto untuk dihapus
    $stmt = $conn->prepare("SELECT foto_klien FROM tb_testimoni WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['foto_klien']) {
            $file_path = '../uploads/' . $row['foto_klien'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM tb_testimoni WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Testimoni berhasil dihapus';
    } else {
        $error = 'Gagal menghapus testimoni';
    }
    $stmt->close();
}

// Get all testimoni
$testimoni_list = $conn->query("SELECT * FROM tb_testimoni ORDER BY id DESC");
?>

<div class="space-y-6">
    <!-- Add Testimoni Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Tambah Testimoni Baru</h3>

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
                <!-- Nama Klien -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Klien</label>
                    <input 
                        type="text" 
                        name="nama_klien" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="Contoh: Ayu Pertiwi"
                        value="<?php echo htmlspecialchars($nama_klien ?? ''); ?>"
                    >
                </div>

                <!-- Rating Bintang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating Bintang (1-5)</label>
                    <select name="bintang" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <option value="5" selected>⭐⭐⭐⭐⭐ 5 Bintang</option>
                        <option value="4">⭐⭐⭐⭐ 4 Bintang</option>
                        <option value="3">⭐⭐⭐ 3 Bintang</option>
                        <option value="2">⭐⭐ 2 Bintang</option>
                        <option value="1">⭐ 1 Bintang</option>
                    </select>
                </div>
            </div>

            <!-- Ulasan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ulasan</label>
                <textarea 
                    name="ulasan" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 resize-none"
                    rows="4"
                    placeholder="Tulis ulasan dari klien"
                ><?php echo htmlspecialchars($ulasan ?? ''); ?></textarea>
            </div>

            <!-- Foto Klien -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Klien (Opsional)</label>
                <input 
                    type="file" 
                    name="foto_klien" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                    accept="image/*"
                >
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, atau GIF (max 2MB)</p>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors"
            >
                Tambah Testimoni
            </button>
        </form>
    </div>

    <!-- Testimoni List -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Testimoni</h3>

        <?php if ($testimoni_list->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($row = $testimoni_list->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['nama_klien']); ?></h4>
                                    <div class="text-yellow-400">
                                        <?php for ($i = 0; $i < $row['bintang']; $i++): ?>
                                            ⭐
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">"<?php echo htmlspecialchars(substr($row['ulasan'], 0, 150)); ?>..."</p>
                            </div>
                            <div class="ml-4 flex gap-2">
                                <a href="?page=testimoni&delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-700 text-sm font-medium" onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">Belum ada testimoni. Silakan tambahkan testimoni baru.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
