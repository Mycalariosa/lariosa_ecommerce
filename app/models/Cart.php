<?php

namespace App\Models;

use PDO;
use PDOException;

class Cart
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function addItem($productId, $quantity)
    {
        try {
            // Check if the product already exists in the cart
            $sql = "SELECT id, quantity FROM cart WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':product_id' => $productId]);
            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cartItem) {
                // Update quantity if the product already exists in the cart
                $newQuantity = $cartItem['quantity'] + $quantity;
                $updateSql = "UPDATE cart SET quantity = :quantity WHERE id = :id";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->execute([':quantity' => $newQuantity, ':id' => $cartItem['id']]);
            } else {
                // Insert new product into the cart
                $insertSql = "INSERT INTO cart (product_id, quantity, created_at) 
                              VALUES (:product_id, :quantity, NOW())";
                $insertStmt = $this->db->prepare($insertSql);
                $insertStmt->execute([':product_id' => $productId, ':quantity' => $quantity]);
            }

            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to add item to cart: " . $e->getMessage());
        }
    }

    // ...existing code...
}