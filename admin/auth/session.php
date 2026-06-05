<?php
/**
 * Session Management
 * Mengelola session admin
 */

// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Cek apakah admin sudah login
 * Return: true jika sudah login, false jika belum
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Set session login
 */
function setLoginSession($admin_id, $username) {
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['admin_username'] = $username;
    $_SESSION['login_time'] = time();
}

/**
 * Destroy session
 */
function destroySession() {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
}

/**
 * Redirect ke halaman login jika belum login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/auth/login.php');
        exit();
    }
}

/**
 * Redirect ke dashboard jika sudah login
 */
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: /project-client-website-php/website_jasa/1_website_jasa_dekorasi_pernikahan_event/admin/dashboard.php');
        exit();
    }
}

?>
