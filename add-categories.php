<?php
require 'vendor/autoload.php';

use Aries\MiniFrameworkStore\Models\Category;

$categories = new Category();

// List of categories to add
$categoryList = [
    'Dresses',
    'Skirts',
    'Jeans',
    'Tops',
    'Shorts',
    'Shoes',
    'On Offer',
    'Accessories'
];

// Add each category
foreach ($categoryList as $categoryName) {
    $sql = "INSERT INTO product_categories (name) VALUES (:name)";
    $stmt = $categories->getConnection()->prepare($sql);
    $stmt->execute(['name' => $categoryName]);
}

echo "Categories added successfully!";
?> 