<?php
require 'vendor/autoload.php';

use Aries\MiniFrameworkStore\Models\Category;

$categories = new Category();

// New list of categories
$categoryList = [
    'Dresses',
    'Tops',
    'Accessories',
    'Bottoms',
    'Costumes'
];

// Get existing categories
$existingCategories = $categories->getAll();
$existingCategoryNames = array_column($existingCategories, 'name');

// Update or add categories
foreach ($categoryList as $index => $categoryName) {
    if (in_array($categoryName, $existingCategoryNames)) {
        // Category exists, update its position if needed
        $categoryId = $existingCategories[array_search($categoryName, $existingCategoryNames)]['id'];
        $sql = "UPDATE product_categories SET name = :name WHERE id = :id";
        $stmt = $categories->getConnection()->prepare($sql);
        $stmt->execute([
            'name' => $categoryName,
            'id' => $categoryId
        ]);
    } else {
        // Add new category
        $sql = "INSERT INTO product_categories (name) VALUES (:name)";
        $stmt = $categories->getConnection()->prepare($sql);
        $stmt->execute(['name' => $categoryName]);
    }
}

// Remove categories that are not in the new list
foreach ($existingCategories as $category) {
    if (!in_array($category['name'], $categoryList)) {
        // Check if category is used by any products
        $sql = "SELECT COUNT(*) FROM products WHERE category_id = :category_id";
        $stmt = $categories->getConnection()->prepare($sql);
        $stmt->execute(['category_id' => $category['id']]);
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            // Only delete if no products are using this category
            $sql = "DELETE FROM product_categories WHERE id = :id";
            $stmt = $categories->getConnection()->prepare($sql);
            $stmt->execute(['id' => $category['id']]);
        }
    }
}

echo "Categories updated successfully!";
?> 