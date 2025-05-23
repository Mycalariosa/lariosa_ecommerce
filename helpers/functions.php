<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (!function_exists('assets')) {
    function assets($path) {
        include 'assets/' . $path;
    }
}

if (!function_exists('template')) {
    function template($file, $options = []) {
        if ($file === 'header.php') {
            $hideButtons = $options['hide_buttons'] ?? false;

            if ($hideButtons) {
                // Exclude "Home" and "Add Product" buttons
                ?>
                <style>
                    .header-buttons {
                        display: none;
                    }
                </style>
                <?php
            }
        }

        include 'templates/' . $file;
    }
}

if (!function_exists('countCart')) {
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
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        if(isset($_SESSION['user'])) {
            return true;
        }

        return false;
    }
}

if (!function_exists('getDatabaseConnection')) {
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
}

if (!function_exists('getOrderStatusBadge')) {
    function getOrderStatusBadge($status) {
        $badges = [
            'Pending' => '<span class="badge badge-warning">Pending</span>',
            'Completed' => '<span class="badge badge-success">Completed</span>',
            'Cancelled' => '<span class="badge badge-danger">Cancelled</span>',
        ];
        return $badges[$status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
}

global $db;
$db = getDatabaseConnection();