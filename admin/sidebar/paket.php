<?php
/**
 * Paket Content (untuk ditampilkan di dashboard.php)
 */

// ============ CRUD Logic ============
$error = '';
$success = '';

// Proses tambah paket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $id_kategori = intval($_POST['id_kategori'] ?? 0);
    $nama_paket = trim($_POST['nama_paket'] ?? '');
    $harga = str_replace(',', '', trim($_POST['harga'] ?? '0'));
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $fitur = trim($_POST['fitur'] ?? '');

    // Validasi
    if ($id_kategori === 0) {
        $error = 'Pilih kategori terlebih dahulu';
    } elseif (empty($nama_paket)) {
        $error = 'Nama paket tidak boleh kosong';
    } elseif ($harga <= 0) {
        $error = 'Harga harus lebih dari 0';
    } else {
        $foto = null;
        
        // Handle file upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['foto']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF';
            } else {
                $new_filename = 'paket_' . time() . '.' . $ext;
                $upload_path = 'uploads/' . $new_filename;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                    $foto = $new_filename;
                } else {
                    $error = 'Gagal upload foto';
                }
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO tb_paket (id_kategori, nama_paket, harga, deskripsi, fitur, foto) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isdsss", $id_kategori, $nama_paket, $harga, $deskripsi, $fitur, $foto);

            if ($stmt->execute()) {
                $success = 'Paket berhasil ditambahkan';
                $id_kategori = '';
                $nama_paket = '';
                $harga = '';
                $deskripsi = '';
                $fitur = '';
            } else {
                $error = 'Gagal menambahkan paket';
            }
            $stmt->close();
        }
    }
}

// Proses hapus paket
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get foto untuk dihapus
    $stmt = $conn->prepare("SELECT foto FROM tb_paket WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['foto']) {
            $file_path = 'uploads/' . $row['foto'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM tb_paket WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Paket berhasil dihapus';
    } else {
        $error = 'Gagal menghapus paket';
    }
    $stmt->close();
    
    // Redirect untuk clear URL
    header("Location: dashboard.php?page=paket");
    exit;
}

// Get all kategori
$kategori_result = $conn->query("SELECT id, nama_kategori FROM tb_kategori ORDER BY nama_kategori");

// Get all paket
$paket_list = $conn->query("
    SELECT p.*, k.nama_kategori 
    FROM tb_paket p 
    LEFT JOIN tb_kategori k ON p.id_kategori = k.id 
    ORDER BY p.id DESC
");
?>

<div class="space-y-6">
    <!-- Add Paket Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Tambah Paket Baru</h3>

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
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="id_kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <option value="">-- Pilih Kategori --</option>
                        <?php while ($cat = $kategori_result->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nama_kategori']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Nama Paket -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket</label>
                    <input 
                        type="text" 
                        name="nama_paket" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="Contoh: Paket Basic, Paket Premium"
                        value="<?php echo htmlspecialchars($nama_paket ?? ''); ?>"
                    >
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input 
                        type="number" 
                        name="harga" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="Contoh: 5000000"
                        value="<?php echo htmlspecialchars($harga ?? ''); ?>"
                    >
                </div>

                <!-- Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Paket</label>
                    <input 
                        type="file" 
                        name="foto" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        accept="image/*"
                    >
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, atau GIF (max 2MB)</p>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 resize-none"
                    rows="3"
                    placeholder="Jelaskan detail paket dekorasi ini"
                ><?php echo htmlspecialchars($deskripsi ?? ''); ?></textarea>
            </div>

            <!-- Fitur -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fitur / Fasilitas</label>
                <textarea 
                    name="fitur" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 resize-none"
                    rows="3"
                    placeholder="Cantumkan fitur yang disediakan (pisahkan dengan Enter)"
                ><?php echo htmlspecialchars($fitur ?? ''); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Pisahkan setiap fitur dengan baris baru</p>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition-colors"
            >
                Tambah Paket
            </button>
        </form>
    </div>

    <!-- Paket List -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Paket</h3>

        <?php if ($paket_list->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($row = $paket_list->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['nama_paket']); ?></h4>
                                <p class="text-sm text-gray-600 mt-1">Kategori: <?php echo htmlspecialchars($row['nama_kategori'] ?? '-'); ?></p>
                                <p class="text-sm font-medium text-rose-600 mt-2">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                <p class="text-sm text-gray-600 mt-2"><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)); ?>...</p>
                            </div>
                            <div class="ml-4 flex gap-2">
                                <a href="dashboard.php?page=paket&delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-700 text-sm font-medium" onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">Belum ada paket. Silakan tambahkan paket baru.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
