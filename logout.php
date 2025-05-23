<?php
session_start();
include 'helpers/functions.php';
use App\Models\User;

// If there’s a remember-me cookie, clear it in the DB too
if (isset($_COOKIE['rememberme'])) {
    $token = $_COOKIE['rememberme'];

    // Clear cookie on client
    setcookie('rememberme', '', time() - 3600, '/');

    // Clear token in database
    $userModel = new User();
    $userModel->clearRememberTokenByToken($token);
}

// Unset all session data
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit;
