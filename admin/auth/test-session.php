<?php
/**
 * Test Session Debugging
 * File untuk debug apakah session bekerja dengan baik
 */

// Start output buffering untuk bisa check headers
ob_start();

echo "<h1>Debug Session Info</h1>";

// Check current session status SEBELUM include
echo "<h3>Sebelum include session.php:</h3>";
echo "session_status() = " . session_status() . " (1=none, 2=active)<br>";

require_once '../../config.php';
require_once 'session.php';

echo "<h3>Sesudah include session.php:</h3>";
echo "session_status() = " . session_status() . "<br>";
echo "session_id() = " . session_id() . "<br>";

echo "<h3>Session Data (\$_SESSION):</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Function Tests:</h3>";
if (isLoggedIn()) {
    echo "✓ <strong>isLoggedIn() = TRUE</strong><br>";
    echo "  admin_id: " . $_SESSION['admin_id'] . "<br>";
    echo "  admin_username: " . $_SESSION['admin_username'] . "<br>";
} else {
    echo "✗ <strong>isLoggedIn() = FALSE</strong><br>";
}

echo "<h3>Query Parameters:</h3>";
if ($_GET['test_login'] ?? false) {
    echo "Testing setLoginSession()...<br>";
    setLoginSession(1, 'testadmin');
    echo "✓ setLoginSession(1, 'testadmin') called<br>";
    echo "  admin_id: " . $_SESSION['admin_id'] . "<br>";
    echo "  admin_username: " . $_SESSION['admin_username'] . "<br>";
    echo "<br><strong>Refresh halaman ini untuk verify session persists</strong><br>";
}

echo "<h3>Server Info:</h3>";
echo "REQUEST_SCHEME: " . $_SERVER['REQUEST_SCHEME'] . "<br>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";

// End output buffering
ob_end_flush();

?>

