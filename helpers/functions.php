<?php
require_once __DIR__ . '/../vendor/autoload.php';

function assets($path) {
    include 'assets/' . $path;
}

function template($path) {
    include 'templates/' . $path;
}

function countCart() {
    if (isset($_SESSION['cart'])) {
        $totalItems = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalItems += $item['quantity'];
        }
        return $totalItems;
    }
    return 0;
}

function isLoggedIn() {
    if(isset($_SESSION['user'])) {
        return true;
    }

    return false;
}

function getDatabaseConnection()
{
    try {
        $host = '127.0.0.1'; // Ensure this matches your database host
        $dbname = 'ecommerce'; // Corrected database name
        $username = 'root'; // Ensure this matches your database username
        $password = ''; // Ensure this matches your database password

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

global $db;
$db = getDatabaseConnection();