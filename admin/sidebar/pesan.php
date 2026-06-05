<?php
/**
 * Pesan Content (untuk ditampilkan di dashboard.php)
 */

// ============ CRUD Logic ============
$error = '';
$success = '';

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
    
    // Redirect untuk clear URL
    header("Location: dashboard.php?page=pesan");
    exit;
}

// Proses tandai sebagai sudah dibaca
if (isset($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);
    $stmt = $conn->prepare("UPDATE tb_pesan SET status_baca = 'sudah' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: dashboard.php?page=pesan");
    exit;
}

// Get all pesan
$pesan_list = $conn->query("SELECT * FROM tb_pesan ORDER BY tanggal_kirim DESC");
?>

<div class="space-y-6">
    <!-- Pesan Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php
        $stats_belum = $conn->query("SELECT COUNT(*) as count FROM tb_pesan WHERE status_baca = 'belum'")->fetch_assoc();
        $stats_sudah = $conn->query("SELECT COUNT(*) as count FROM tb_pesan WHERE status_baca = 'sudah'")->fetch_assoc();
        $stats_total = $conn->query("SELECT COUNT(*) as count FROM tb_pesan")->fetch_assoc();
        ?>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm p-6 border border-blue-200">
            <p class="text-sm text-blue-600 font-medium">Pesan Belum Dibaca</p>
            <p class="text-3xl font-bold text-blue-900 mt-2"><?php echo $stats_belum['count']; ?></p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm p-6 border border-green-200">
            <p class="text-sm text-green-600 font-medium">Pesan Sudah Dibaca</p>
            <p class="text-3xl font-bold text-green-900 mt-2"><?php echo $stats_sudah['count']; ?></p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-sm p-6 border border-purple-200">
            <p class="text-sm text-purple-600 font-medium">Total Pesan</p>
            <p class="text-3xl font-bold text-purple-900 mt-2"><?php echo $stats_total['count']; ?></p>
        </div>
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
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Pesan Masuk</h3>

        <?php if ($pesan_list->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($row = $pesan_list->fetch_assoc()): 
                    $is_unread = $row['status_baca'] === 'belum';
                ?>
                    <div class="border <?php echo $is_unread ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200'; ?> rounded-lg p-4 hover:shadow-sm transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Header -->
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['nama_pengirim']); ?></h4>
                                    <?php if ($is_unread): ?>
                                        <span class="px-2 py-1 bg-yellow-200 text-yellow-800 text-xs font-medium rounded-full">Belum Dibaca</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-gray-200 text-gray-800 text-xs font-medium rounded-full">Sudah Dibaca</span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Contact Info -->
                                <p class="text-sm text-gray-600">
                                    Email: <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-rose-600 hover:underline"><?php echo htmlspecialchars($row['email']); ?></a>
                                </p>
                                <p class="text-sm text-gray-600">
                                    WhatsApp: <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $row['no_whatsapp']); ?>" target="_blank" class="text-rose-600 hover:underline"><?php echo htmlspecialchars($row['no_whatsapp']); ?></a>
                                </p>

                                <!-- Message -->
                                <p class="text-sm text-gray-700 mt-3 p-3 bg-gray-50 rounded border-l-4 border-rose-600">
                                    <?php echo htmlspecialchars($row['pesan']); ?>
                                </p>

                                <!-- Tanggal -->
                                <p class="text-xs text-gray-500 mt-2">
                                    Dikirim: <?php echo date('d F Y H:i', strtotime($row['tanggal_kirim'])); ?>
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="ml-4 flex flex-col gap-2">
                                <?php if ($is_unread): ?>
                                    <a href="dashboard.php?page=pesan&mark_read=<?php echo $row['id']; ?>" class="px-3 py-1 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700">
                                        Baca
                                    </a>
                                <?php endif; ?>
                                <a href="dashboard.php?page=pesan&delete=<?php echo $row['id']; ?>" class="px-3 py-1 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700" onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">Tidak ada pesan masuk.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
