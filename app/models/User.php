<?php

namespace Aries\MiniFrameworkStore\Models;

use Aries\MiniFrameworkStore\Includes\Database;


class User extends Database {
    private $db;
    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'customer';

    public function __construct() {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function login($data) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'email' => $data['email'],
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    // Save or clear the remember me token for a user
public function saveRememberToken($userId, $token) {
    $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':token' => $token,
        ':id' => $userId,
    ]);
}

// Get user by remember me token
public function getUserByRememberToken($token) {
    $sql = "SELECT * FROM users WHERE remember_token = :token LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':token' => $token]);
    return $stmt->fetch();
}


    public function register($data) {
        $sql = "INSERT INTO users (name, email, password, address, phone, birthdate, role, created_at, updated_at) 
                VALUES (:name, :email, :password, :address, :phone, :birthdate, :role, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'role' => self::ROLE_CUSTOMER, // Default role for new registrations
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);

        return $this->db->lastInsertId();
    }

    public function update($data) {
        $sql = "UPDATE users SET name = :name, email = :email, address = :address, phone = :phone, birthdate = :birthdate, role = :role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'birthdate' => $data['birthdate'],
            'role' => $data['role'] ?? self::ROLE_CUSTOMER
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
    }   

    public function isAdmin($userId) {
        $sql = "SELECT role FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user && $user['role'] === self::ROLE_ADMIN;
    }

    public function getAllAdmins() {
        $sql = "SELECT * FROM users WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['role' => self::ROLE_ADMIN]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllCustomers() {
        $sql = "SELECT * FROM users WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['role' => self::ROLE_CUSTOMER]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}