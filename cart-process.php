<?php
   
   require 'vendor/autoload.php';

    use Aries\MiniFrameworkStore\Models\Product;

    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'auth_required']);
        exit;
    }

    $product_id = intval($_POST['productId']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product = new Product();
    $productDetails = $product->getById($_POST['productId']);

    // Ensure the cart only includes product ID and quantity
    $_SESSION['cart'][$product_id] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'name' => $productDetails['name'],
        'price' => $productDetails['price'],
        'image_path' => $productDetails['image_path'],
        'total' => $productDetails['price'] * $quantity
    ];

    echo json_encode(['status' => 'success']);

?>