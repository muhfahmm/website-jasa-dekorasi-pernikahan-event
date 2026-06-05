<?php
/**
 * Portofolio Management
 * Halaman untuk mengelola portofolio / galeri
 */

require_once __DIR__ . '/../../config.php';

$error = '';
$success = '';

// Proses tambah portofolio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tanggal_event = trim($_POST['tanggal_event'] ?? '');

    // Validasi
    if (empty($judul)) {
        $error = 'Judul portofolio tidak boleh kosong';
    } elseif (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Pilih file foto terlebih dahulu';
    } else {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF';
        } else {
            $new_filename = 'portfolio_' . time() . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                $stmt = $conn->prepare("INSERT INTO tb_portofolio (judul, deskripsi, foto, tanggal_event) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $judul, $deskripsi, $new_filename, $tanggal_event);

                if ($stmt->execute()) {
                    $success = 'Portofolio berhasil ditambahkan';
                    $judul = '';
                    $deskripsi = '';
                    $tanggal_event = '';
                } else {
                    $error = 'Gagal menambahkan portofolio';
                    unlink($upload_path);
                }
                $stmt->close();
            } else {
                $error = 'Gagal upload foto';
            }
        }
    }
}

// Proses hapus portofolio
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get foto untuk dihapus
    $stmt = $conn->prepare("SELECT foto FROM tb_portofolio WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = '../uploads/' . $row['foto'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM tb_portofolio WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Portofolio berhasil dihapus';
    } else {
        $error = 'Gagal menghapus portofolio';
    }
    $stmt->close();
}

// Get all portofolio
$portofolio_list = $conn->query("SELECT * FROM tb_portofolio ORDER BY id DESC");
?>

<div class="space-y-6">
    <!-- Add Portofolio Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Tambah Portofolio Baru</h3>

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
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Portofolio</label>
                    <input 
                        type="text" 
                        name="judul" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        placeholder="Contoh: Pernikahan Ayu & Rudi"
                        value="<?php echo htmlspecialchars($judul ?? ''); ?>"
                    >
                </div>

                <!-- Tanggal Event -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Event</label>
                    <input 
                        type="date" 
                        name="tanggal_event" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                        value="<?php echo htmlspecialchars($tanggal_event ?? ''); ?>"
                    >
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Portofolio</label>
                <textarea 
                    name="deskripsi" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 resize-none"
                    rows="3"
                    placeholder="Jelaskan detail acara dan dekorasi yang digunakan"
                ><?php echo htmlspecialchars($deskripsi ?? ''); ?></textarea>
            </div>

            <!-- Foto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Portofolio</label>
                <input 
                    type="file" 
                    name="foto" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500"
                    accept="image/*"
                >
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, atau GIF (max 2MB)</p>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors"
            >
                Tambah Portofolio
            </button>
        </form>
    </div>

    <!-- Portofolio List -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Portofolio</h3>

        <?php if ($portofolio_list->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($row = $portofolio_list->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['judul']); ?></h4>
                                <?php if ($row['tanggal_event']): ?>
                                    <p class="text-sm text-gray-600 mt-1">Tanggal Event: <?php echo date('d F Y', strtotime($row['tanggal_event'])); ?></p>
                                <?php endif; ?>
                                <p class="text-sm text-gray-600 mt-2"><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)); ?>...</p>
                            </div>
                            <div class="ml-4 flex gap-2">
                                <a href="?page=portofolio&delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-700 text-sm font-medium" onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">Belum ada portofolio. Silakan tambahkan portofolio baru.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
