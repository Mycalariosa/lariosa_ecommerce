<?php
session_start();
include 'helpers/functions.php';
use App\Models\User;

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $userId = $_SESSION['user']['id'];
    
    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        $_SESSION['error'] = "Only JPG, PNG and GIF files are allowed.";
        header('Location: my-account.php');
        exit();
    }
    
    if ($file['size'] > $maxSize) {
        $_SESSION['error'] = "File size must be less than 5MB.";
        header('Location: my-account.php');
        exit();
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
    $uploadPath = 'assets/images/profile_pictures/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Update database
        $userModel = new User();
        $userModel->update([
            'id' => $userId,
            'profile_picture' => $uploadPath
        ]);
        
        // Update session
        $_SESSION['user']['profile_picture'] = $uploadPath;
    } else {
        $_SESSION['error'] = "Failed to upload profile picture.";
    }
    
    header('Location: my-account.php');
    exit();
} 