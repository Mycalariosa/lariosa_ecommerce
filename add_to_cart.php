<?php
include 'helpers/functions.php';
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Product;

session_start();

// Ensure $db is a valid PDO instance
global $db;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);

    if ($productId) {
        try {
            // Validate product existence
            $product = new Product($db);
            $productData = $product->getById($productId);

            if (!$productData) {
                echo json_encode(['success' => false, 'error' => 'Product not found']);
                exit;
            }

            // Initialize cart session if not already set
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Add or update product in the cart
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += 1;
                $_SESSION['cart'][$productId]['total'] = $_SESSION['cart'][$productId]['quantity'] * $productData['price'];
            } else {
                $_SESSION['cart'][$productId] = [
                    'product_id' => $productId,
                    'quantity' => 1,
                    'name' => $productData['name'],
                    'price' => $productData['price'],
                    'image_path' => $productData['image_path'],
                    'total' => $productData['price']
                ];
            }

            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            error_log("Error in add_to_cart.php: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'An internal error occurred.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid product ID.']);
        exit;
    }
}

// Return error response for invalid request method
echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
exit;