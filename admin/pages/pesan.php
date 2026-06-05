<?php
/**
 * Pesan Masuk Management
 * Halaman untuk melihat pesan masuk dari pelanggan
 */

require_once __DIR__ . '/../../config.php';

$error = '';
$success = '';

// Proses tandai sebagai sudah dibaca
if (isset($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);
    $stmt = $conn->prepare("UPDATE tb_pesan SET status_baca = 'sudah' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Pesan ditandai sebagai sudah dibaca';
    }
    $stmt->close();
}

// Proses tandai sebagai belum dibaca
if (isset($_GET['mark_unread'])) {
    $id = intval($_GET['mark_unread']);
    $stmt = $conn->prepare("UPDATE tb_pesan SET status_baca = 'belum' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Pesan ditandai sebagai belum dibaca';
    }
    $stmt->close();
}

// Proses hapus pesan
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM tb_pesan WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Pesan berhasil dihapus';
    } else {
        $error = 'Gagal menghapus pesan';
    }
    $stmt->close();
}

// Get filter
$filter = $_GET['filter'] ?? 'all';
$filter_text = '';

// Get all pesan
if ($filter === 'belum') {
    $pesan_list = $conn->query("SELECT * FROM tb_pesan WHERE status_baca = 'belum' ORDER BY tanggal_kirim DESC");
    $filter_text = 'Belum Dibaca';
} elseif ($filter === 'sudah') {
    $pesan_list = $conn->query("SELECT * FROM tb_pesan WHERE status_baca = 'sudah' ORDER BY tanggal_kirim DESC");
    $filter_text = 'Sudah Dibaca';
} else {
    $pesan_list = $conn->query("SELECT * FROM tb_pesan ORDER BY tanggal_kirim DESC");
    $filter_text = 'Semua Pesan';
}

// Get counts
$count_result = $conn->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status_baca = 'belum' THEN 1 ELSE 0 END) as belum,
        SUM(CASE WHEN status_baca = 'sudah' THEN 1 ELSE 0 END) as sudah
    FROM tb_pesan
");
$counts = $count_result->fetch_assoc();
?>

<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="?page=pesan&filter=all" class="<?php echo $filter === 'all' ? 'bg-blue-50 border-blue-300' : 'bg-white border-gray-200 hover:shadow-sm'; ?> rounded-lg shadow-sm p-4 border transition-all cursor-pointer">
            <p class="text-gray-600 text-sm">Total Pesan</p>
            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $counts['total']; ?></p>
        </a>

        <a href="?page=pesan&filter=belum" class="<?php echo $filter === 'belum' ? 'bg-red-50 border-red-300' : 'bg-white border-gray-200 hover:shadow-sm'; ?> rounded-lg shadow-sm p-4 border transition-all cursor-pointer">
            <p class="text-gray-600 text-sm">Belum Dibaca</p>
            <p class="text-3xl font-bold text-red-600 mt-2"><?php echo $counts['belum'] ?? 0; ?></p>
        </a>

        <a href="?page=pesan&filter=sudah" class="<?php echo $filter === 'sudah' ? 'bg-green-50 border-green-300' : 'bg-white border-gray-200 hover:shadow-sm'; ?> rounded-lg shadow-sm p-4 border transition-all cursor-pointer">
            <p class="text-gray-600 text-sm">Sudah Dibaca</p>
            <p class="text-3xl font-bold text-green-600 mt-2"><?php echo $counts['sudah'] ?? 0; ?></p>
        </a>
    </div>

    <!-- Messages -->
    <?php if ($error): ?>
        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700 text-sm">⚠️ <?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-700 text-sm">✓ <?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>

    <!-- Pesan List -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800"><?php echo $filter_text; ?></h3>
            <div class="text-sm text-gray-600">
                Total: <span class="font-medium"><?php echo $pesan_list->num_rows; ?></span>
            </div>
        </div>

        <?php if ($pesan_list->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($row = $pesan_list->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-all <?php echo $row['status_baca'] === 'belum' ? 'bg-blue-50 border-blue-200' : 'bg-white'; ?>">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Header -->
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['nama_pengirim']); ?></h4>
                                    
                                    <!-- Status Badge -->
                                    <?php if ($row['status_baca'] === 'belum'): ?>
                                        <span class="inline-block px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">Belum Dibaca</span>
                                    <?php else: ?>
                                        <span class="inline-block px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Sudah Dibaca</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Contact Info -->
                                <div class="flex flex-col gap-1 mb-3">
                                    <?php if ($row['email']): ?>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Email:</span> 
                                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-rose-600 hover:underline">
                                                <?php echo htmlspecialchars($row['email']); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">WhatsApp:</span> 
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $row['no_whatsapp']); ?>" target="_blank" class="text-rose-600 hover:underline">
                                            <?php echo htmlspecialchars($row['no_whatsapp']); ?>
                                        </a>
                                    </p>
                                </div>

                                <!-- Message -->
                                <p class="text-sm text-gray-700 bg-white rounded p-3 border border-gray-300 mb-2">
                                    "<?php echo htmlspecialchars($row['pesan']); ?>"
                                </p>

                                <!-- Date -->
                                <p class="text-xs text-gray-500">
                                    <?php echo date('d F Y \p\u\k\u\l H:i', strtotime($row['tanggal_kirim'])); ?>
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="ml-4 flex flex-col gap-2">
                                <?php if ($row['status_baca'] === 'belum'): ?>
                                    <a href="?page=pesan&mark_read=<?php echo $row['id']; ?>" class="text-green-600 hover:text-green-700 text-sm font-medium">
                                        Tandai Dibaca
                                    </a>
                                <?php else: ?>
                                    <a href="?page=pesan&mark_unread=<?php echo $row['id']; ?>" class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                                        Tandai Belum
                                    </a>
                                <?php endif; ?>
                                <a href="?page=pesan&delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-700 text-sm font-medium" onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-500 mb-2">Tidak ada pesan untuk kategori ini.</p>
                <a href="?page=pesan" class="text-rose-600 hover:text-rose-700 text-sm font-medium">
                    Lihat semua pesan
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
