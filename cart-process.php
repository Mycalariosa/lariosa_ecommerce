<?php
require 'vendor/autoload.php';

use App\Models\Product;

session_start();

// Ensure $db is initialized
global $db;

// Retrieve and sanitize input
$productId = filter_input(INPUT_POST, 'productId', FILTER_SANITIZE_NUMBER_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT) ?: 1; // Default quantity to 1 if not provided

if (!$productId || $quantity <= 0) {
    echo json_encode(['status' => 'error', 'error' => 'Invalid product ID or quantity.']);
    exit;
}

// Initialize Product model
$product = new Product($db);

try {
    // Validate product existence
    $productDetails = $product->getById($productId);
    if (!$productDetails) {
        echo json_encode(['status' => 'error', 'error' => 'Product not found.']);
        exit;
    }

    // Initialize cart session if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or update product in the cart
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
        $_SESSION['cart'][$productId]['total'] = $_SESSION['cart'][$productId]['quantity'] * $productDetails['price'];
    } else {
        $_SESSION['cart'][$productId] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'name' => $productDetails['name'],
            'price' => $productDetails['price'],
            'image_path' => $productDetails['image_path'],
            'total' => $productDetails['price'] * $quantity
        ];
    }

    // Calculate the new total items in the cart
    $totalItems = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalItems += $item['quantity'];
    }

    echo json_encode(['status' => 'success', 'totalItems' => $totalItems]);
    exit;
} catch (Exception $e) {
    error_log("Error in cart-process.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'error' => 'An internal error occurred.']);
    exit;
}
?>