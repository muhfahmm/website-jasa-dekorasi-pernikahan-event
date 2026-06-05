<?php
/**
 * Script untuk menghapus tabel tb_gambar_produk dari database
 * 
 * PETUNJUK:
 * 1. Akses file ini via browser: http://localhost/.../admin/cleanup/delete_gambar_table.php
 * 2. Atau jalankan melalui command line: php delete_gambar_table.php
 * 3. Tabel tb_gambar_produk akan dihapus dari database
 */

require_once '../../config.php';

echo "=== DELETE GAMBAR PRODUK TABLE ===\n\n";

// Cek apakah tabel ada
$check_table = $conn->query("SHOW TABLES LIKE 'tb_gambar_produk'");

if ($check_table->num_rows > 0) {
    echo "📊 Tabel tb_gambar_produk ditemukan. Menghapus...\n\n";
    
    // Hapus tabel
    $drop_result = $conn->query("DROP TABLE tb_gambar_produk");
    
    if ($drop_result) {
        echo "✅ SUCCESS: Tabel tb_gambar_produk berhasil dihapus!\n\n";
        
        // Verifikasi
        $verify = $conn->query("SHOW TABLES LIKE 'tb_gambar_produk'");
        if ($verify->num_rows == 0) {
            echo "✅ VERIFIED: Tabel sudah tidak ada di database.\n\n";
            echo "📋 Tabel yang tersisa:\n";
            $tables = $conn->query("SHOW TABLES");
            while ($row = $tables->fetch_array()) {
                echo "   - " . $row[0] . "\n";
            }
        }
    } else {
        echo "❌ ERROR: Gagal menghapus tabel: " . $conn->error . "\n";
    }
} else {
    echo "ℹ️  Tabel tb_gambar_produk tidak ditemukan.\n";
    echo "   (Mungkin sudah dihapus sebelumnya)\n\n";
    
    echo "📋 Tabel yang ada di database:\n";
    $tables = $conn->query("SHOW TABLES");
    while ($row = $tables->fetch_array()) {
        echo "   - " . $row[0] . "\n";
    }
}

echo "\n=== SELESAI ===\n";
$conn->close();
?>
