<?php

namespace App\Models;

use App\Includes\Database;

class User extends Database {
    private $db;
    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'customer';

    public function __construct() {
        parent::__construct();
        $this->db = $this->getConnection();
    }

    // Improved login method:
    public function login($data) {
        // Case-insensitive email match
        $sql = "SELECT * FROM users WHERE LOWER(email) = LOWER(:email) LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'email' => $data['email'],
        ]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Verify password
        if ($user && password_verify($data['password'], $user['password'])) {
            return $user;
        }
        
        return false;
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
            'role' => self::ROLE_CUSTOMER,
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);

        return $this->db->lastInsertId();
    }

    public function update($data) {
        $fields = [];
        $params = ['id' => $data['id']];
        $allowedFields = ['name', 'email', 'address', 'phone', 'birthdate', 'profile_picture'];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }
        if (empty($fields)) {
            return false;
        }
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
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
    // In App\Models\User
public function clearRememberTokenByToken(string $token): bool
{
    // assuming your DB table has columns `remember_token` and you can run a raw query or use your ORM
    return $this->db->table('users')
                    ->where('remember_token', $token)
                    ->update(['remember_token' => null]);
}


    public function find($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
