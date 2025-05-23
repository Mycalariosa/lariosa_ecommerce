<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'helpers/functions.php';
require_once __DIR__ . '/vendor/autoload.php';

// Require admin access
require_once 'helpers/role_helper.php';
requireAdmin();

use App\Models\Product;

// Ensure $db is initialized
global $db;
$product = new Product($db);

// Handle delete request
if (isset($_GET['delete'])) {
    $productId = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT);
    if ($productId) {
        $product->delete($productId);
        header('Location: product-management.php');
        exit();
    }
}

// Fetch all products
$products = $product->getAll();

template('header.php');
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f7f5;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 80px auto; /* Increased margin to separate header and title */
        padding: 20px;
    }

    .management-header {
        background-color: #2e2e2e;
        color: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
    }

    .management-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .management-card {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .management-card h2 {
        font-size: 20px;
        font-weight: 600;
        color: #222;
        margin-bottom: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th, .table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #333;
    }

    .table tr:hover {
        background-color: #f8f9fa;
    }

    .btn {
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        color: #fff;
        display: inline-block;
        margin-right: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-success {
        background-color: #b08e6b; /* Brown */
    }

    .btn-success:hover {
        background-color: #a07a50; /* Darker brown */
    }

    .btn-primary {
        background-color: #b08e6b; /* Brown for Edit button */
    }

    .btn-primary:hover {
        background-color: #a07a50; /* Darker brown for Edit button */
    }

    .btn-danger {
        background-color: #2e2e2e; /* Black */
    }

    .btn-danger:hover {
        background-color: #1a1a1a; /* Darker black */
    }
</style>

<div class="container">
    <div class="management-header">
        <h1>Project Management</h1> <!-- Updated title -->
    </div>

    <div class="management-card">
        <h2>Manage Products</h2>
        <a href="add-product.php" class="btn btn-success">Add New Product</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['category_id']); ?></td>
                        <td>
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="product-management.php?delete=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php template('footer.php'); ?>
