<?php
include 'helpers/functions.php';

use Aries\MiniFrameworkStore\Models\Cart;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    if ($productId) {
        $cart = new Cart();
        $cart->addItem($productId, $quantity);
        
        // Return success response without notice
        echo json_encode(['success' => true]);
        exit;
    }
}

// Return error response
echo json_encode(['success' => false]);
exit; 