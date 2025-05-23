<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'helpers/functions.php';
require_once 'helpers/role_helper.php';
require_once __DIR__ . '/vendor/autoload.php';

// Require admin access
requireAdmin();

use App\Models\Category;
use App\Models\roduct;
use Carbon\Carbon;

// Ensure $db is initialized
global $db;

$categories = new Category();
$productModel = new Product($db);

// Get product ID from query string
if (!isset($_GET['id'])) {
    header('Location: product-management.php');
    exit();
}

$productId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$product = $productModel->getById($productId);

if (!$product) {
    header('Location: product-management.php');
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_FILES['image'];

    $targetFile = $product['image_path']; // Default to existing image
    if ($image['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($image["name"]);
        move_uploaded_file($image["tmp_name"], $targetFile);
    }

    $productModel->update([
        'id' => $productId,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'image_path' => $targetFile,
        'category_id' => $category,
        'updated_at' => Carbon::now()
    ]);

    header('Location: product-management.php'); // Redirect after successful update
    exit();
}

template('header.php');
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f7f5;
        color: #333;
        margin: 0;
        padding: 0;
        padding-top: 80px; /* Add padding for fixed header */
    }

    .container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1; /* Ensure content is beneath the fixed header (z-index: 1000) */
    }

    .form-container {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: 1rem;
        max-width: 500px;
        margin: 1rem auto;
        position: relative;
        z-index: 1;
    }

    .form-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        text-align: center;
        font-weight: 600;
        color: #2e2e2e;
    }

    .form-control {
        border: 1px solid #ddd;
        padding: 8px;
        border-radius: 6px;
    }

    .form-control:focus {
        border-color: #b08e6b;
        box-shadow: 0 0 0 0.2rem rgba(176,142,107,0.25);
    }

    .btn-primary {
        background-color: #2e2e2e; /* Black */
    }

    .btn-primary:hover {
        background-color: #1a1a1a; /* Darker black */
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <div class="form-title">Edit Product</div>
                
                <?php if (isset($message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="edit-product.php?id=<?php echo $productId; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="product-name" name="name" placeholder="Product Name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            <label for="product-name">Product Name</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="product-description" name="description" placeholder="Description" style="height: 100px"><?php echo htmlspecialchars($product['description']); ?></textarea>
                            <label for="product-description">Description</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="number" step="0.01" class="form-control" id="product-price" name="price" placeholder="Price" value="<?php echo $product['price']; ?>" required>
                                <label for="product-price">Price</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="product-category" name="category" required>
                                    <option value="" disabled>Select category</option>
                                    <?php foreach($categories->getAll() as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo $category['name']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="product-category">Category</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-group">
                            <label for="formFile" class="form-label">Product Image</label>
                            <input class="form-control" type="file" id="formFile" name="image" accept="image/*">
                            <div class="form-text">Upload a high-quality image (Max size: 5MB). Leave blank to keep the current image.</div>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100" type="submit" name="submit">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>
