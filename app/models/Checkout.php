<?php

namespace App\Models;

use App\Includes\Database;
use Carbon\Carbon;

class Checkout extends Database
{

    private $db;

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function guestCheckout($data)
    {
        $sql = "INSERT INTO orders (customer_id, guest_name, guest_phone, guest_address, total, status, created_at, updated_at) 
                VALUES (:customer_id, :guest_name, :guest_phone, :guest_address, :total, :status, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'customer_id' => null,
            'guest_name' => $data['name'],
            'guest_phone' => $data['phone'],
            'guest_address' => $data['address'],
            'total' => $data['total'],
            'status' => 'Pending', // Automatically set status to Pending
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila')
        ]);

        return $this->db->lastInsertId();
    }

    public function userCheckout($data)
    {
        $sql = "INSERT INTO orders (customer_id, guest_name, guest_phone, guest_address, total, status, created_at, updated_at) 
                VALUES (:customer_id, :guest_name, :guest_phone, :guest_address, :total, :status, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'customer_id' => $data['user_id'],
            'guest_name' => null,
            'guest_phone' => null,
            'guest_address' => null,
            'total' => $data['total'],
            'status' => 'Pending', // Automatically set status to Pending
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila')
        ]);

        return $this->db->lastInsertId();
    }

    public function saveOrderDetails($data)
    {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price, subtotal) VALUES (:order_id, :product_id, :quantity, :price, :subtotal)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'order_id' => $data['order_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'subtotal' => $data['subtotal']
        ]);
    }

    public function getAllOrders()
    {
        $sql = "SELECT o.id, u.name AS user_name, p.name AS product_name, od.quantity, od.price AS total_price, o.created_at AS order_date
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_details od ON o.id = od.order_id
                LEFT JOIN products p ON od.product_id = p.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUserOrders($userId)
    {
        $sql = "SELECT o.id, o.status, o.created_at AS order_date, p.name AS product_name, od.quantity, od.price AS total_price
                FROM orders o
                JOIN order_details od ON o.id = od.order_id
                JOIN products p ON od.product_id = p.id
                WHERE o.customer_id = :user_id
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':order_id' => $orderId
        ]);
        return $stmt->rowCount();
    }

}