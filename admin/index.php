<?php
/**
 * Admin Root Index
 * Redirect ke login.php jika belum login
 * Redirect ke dashboard.php jika sudah login
 */

require_once '../config.php';
require_once 'auth/session.php';

// Jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

// Jika belum login, redirect ke login
header('Location: auth/login.php');
exit();
?>
