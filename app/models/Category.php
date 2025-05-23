<?php

namespace App\Models;

use App\Includes\Database;

class Category extends Database
{

    private $db;

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    public function getAll()
    {
        $sql = "SELECT * FROM product_categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}