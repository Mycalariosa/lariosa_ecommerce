<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear the remember me cookie if it exists
if (isset($_COOKIE['rememberme'])) {
    setcookie('rememberme', '', time() - 3600, '/');
}

// Redirect to home page
header('Location: index.php');
exit;
