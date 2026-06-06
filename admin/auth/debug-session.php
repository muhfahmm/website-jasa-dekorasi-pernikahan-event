<?php
/**
 * Debug Session Check
 * Verifikasi session berjalan dengan benar
 */

require_once '../../config.php';
require_once 'session.php';

// Force clear session untuk test
// session_destroy();

echo "<h2>🔍 Session Debug Info</h2>";
echo "<hr>";

echo "<h3>1. Session Status</h3>";
echo "session_status(): " . session_status() . " (1=none, 2=active)<br>";
echo "session_id(): " . session_id() . "<br>";
echo "Session saved path: " . session_save_path() . "<br>";

echo "<h3>2. Session Data (\$_SESSION)</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>3. isLoggedIn() Check</h3>";
if (isLoggedIn()) {
    echo "✅ <strong>isLoggedIn() = TRUE</strong><br>";
    echo "admin_id: " . $_SESSION['admin_id'] . "<br>";
    echo "admin_username: " . $_SESSION['admin_username'] . "<br>";
} else {
    echo "❌ <strong>isLoggedIn() = FALSE</strong> (NOT LOGGED IN)<br>";
}

echo "<h3>4. PHPSESSID Cookie</h3>";
if (isset($_COOKIE['PHPSESSID'])) {
    echo "✅ PHPSESSID exists: " . substr($_COOKIE['PHPSESSID'], 0, 20) . "...<br>";
} else {
    echo "❌ PHPSESSID NOT found<br>";
}

echo "<h3>5. Test requireLogin()</h3>";
echo "Jika tidak logged in, akan di-redirect ke login.php<br>";
echo "<strong>Anda sekarang: ";
if (isLoggedIn()) {
    echo "SUDAH LOGIN ✅</strong><br>";
} else {
    echo "BELUM LOGIN ❌</strong><br>";
    echo "<br>Redirecting ke login...<br>";
    header('Location: login.php');
    exit();
}

echo "<h3>6. Test Links</h3>";
echo "<a href='login.php'>Go to Login</a> | ";
echo "<a href='../dashboard.php'>Go to Dashboard</a> | ";
echo "<a href='logout.php'>Logout</a>";

?>
