<?php
session_start();

header('Content-Type: application/json');

// Retrieve and sanitize input
$productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);

if (!$productId || !isset($_SESSION['cart'][$productId])) {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID or product not in cart.']);
    exit;
}

// Remove the product from the cart
unset($_SESSION['cart'][$productId]);

// Calculate the new total
$newTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $newTotal += $item['price'] * $item['quantity'];
}

// Check if the cart is empty
$cartEmpty = count($_SESSION['cart']) === 0;

echo json_encode([
    'success' => true,
    'newTotal' => number_format($newTotal, 2),
    'cartEmpty' => $cartEmpty
]);
exit;
