<?php
/**
 * Logout
 * Menghapus session dan mengarahkan ke login
 */

require_once 'session.php';

// Destroy session
destroySession();

// Redirect ke login
header('Location: login.php');
exit();
?>