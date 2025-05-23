<?php

namespace App\Models;

use App\Includes\Database;
use PDO;

class Product extends Database
{
    private $db;

    public function __construct()
    {
        parent::__construct(); // connect to DB
        $this->db = $this->getConnection();
    }

    public function insert($data)
    {
        $sql = "INSERT INTO products (name, description, price, slug, image_path, category_id, created_at, updated_at) 
                VALUES (:name, :description, :price, :slug, :image_path, :category_id, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':slug' => $data['slug'],
            ':image_path' => $data['image_path'],
            ':category_id' => $data['category_id'],
            ':created_at' => $data['created_at'],
            ':updated_at' => $data['updated_at']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE products 
                SET name = :name, description = :description, price = :price, image_path = :image_path 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image_path' => $data['image_path']
        ]);
        return "Record UPDATED successfully";
    }

    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return "Record DELETED successfully";
    }

    public function getAll()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByName($name)
    {
        $sql = "SELECT * FROM products WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByPrice($price)
    {
        $sql = "SELECT * FROM products WHERE price = :price";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':price' => $price]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCategory($category_name)
    {
        $sql = "SELECT p.id, p.name, p.price, p.description, p.image_path
                FROM products p
                JOIN product_categories c ON p.category_id = c.id
                WHERE c.name = :category_name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_name' => $category_name]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
