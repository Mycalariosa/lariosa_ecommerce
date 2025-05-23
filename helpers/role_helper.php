<?php

use App\Models\User;

/**
 * Check if the current user is an admin
 * @return bool
 */
function isAdmin() {
    // Check if user is logged in and user ID is available in session
    if (!isset($_SESSION['user']['id'])) {
        return false;
    }

    // Get the user ID from the session
    $userId = $_SESSION['user']['id'];

    // Fetch user details from the database using the User model
    $userModel = new User();
    $user = $userModel->find($userId);

    // Check if user was found and their role is 'admin'
    if ($user && isset($user['role']) && $user['role'] === 'admin') {
        return true;
    }

    return false;
}

/**
 * Check if the current user is a customer
 * @return bool
 */
function isCustomer() {
    if (!isset($_SESSION['user']['id'])) {
        return false;
    }
     // Get the user ID from the session
    $userId = $_SESSION['user']['id'];

    // Fetch user details from the database using the User model
    $userModel = new User();
    $user = $userModel->find($userId);

    // Check if user was found and their role is 'customer'
    if ($user && isset($user['role']) && $user['role'] === 'customer') {
        return true;
    }
    return false;
}

/**
 * Require admin role for access
 * Redirects to login page if not admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Require customer role for access
 * Redirects to login page if not customer
 */
function requireCustomer() {
    if (!isCustomer()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Get user role
 * @return string|null
 */
function getUserRole() {
     if (!isset($_SESSION['user']['id'])) {
        return null;
    }
     // Get the user ID from the session
    $userId = $_SESSION['user']['id'];

    // Fetch user details from the database using the User model
    $userModel = new User();
    $user = $userModel->find($userId);

    if ($user && isset($user['role'])) {
        return $user['role'];
    }
    return null;
}

/**
 * Check if user has specific role
 * @param string $role
 * @return bool
 */
function hasRole($role) {
    if (!isset($_SESSION['user']['id'])) {
        return false;
    }
     // Get the user ID from the session
    $userId = $_SESSION['user']['id'];

    // Fetch user details from the database using the User model
    $userModel = new User();
    $user = $userModel->find($userId);
    
    if ($user && isset($user['role'])) {
        return $user['role'] === $role;
    }
    return false;
} 