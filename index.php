<?php 
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'helpers/functions.php'; 

use App\Models\Product;
use App\Models\Category;

$products = new Product();
$categories = new Category();
$amountLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amountLocale, NumberFormatter::CURRENCY);

// Get selected category if any
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

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

    /* Header */
    .header-bar {
        background-color: #2e2e2e;
        color: #fff;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .store-logo {
        display: flex;
        align-items: center;
        font-family: 'Georgia', serif;
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1.5px;
    }

    .store-logo img {
        width: 40px;
        height: 40px;
        margin-right: 12px;
    }

    .store-logo span {
        color: #b08e6b;
    }

    .header-links a {
        color: #ddd;
        margin-left: 20px;
        text-decoration: none;
        transition: color 0.3s ease;
        font-size: 14px;
    }

    .header-links a:hover {
        color: #fff;
    }

    /* Layout */
    .fashion-container {
        display: flex;
        max-width: 1200px;
        margin: auto;
        padding: 20px;
        position: relative;
        z-index: 1;
    }

    .sidebar {
        width: 20%;
        background-color: #fff;
        padding: 20px;
        border-right: 1px solid #ddd;
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .sidebar h4 {
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 14px;
        text-transform: uppercase;
    }

    .category-list {
        list-style: none;
        padding: 0;
    }

    .category-list li {
        padding: 10px 0;
        font-size: 14px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .category-list li a {
        color: #333;
        text-decoration: none;
        display: block;
        transition: color 0.3s ease;
    }

    .category-list li a:hover {
        color: #b08e6b;
    }

    .category-list li a.active {
        color: #b08e6b;
        font-weight: 600;
    }

    .main-content {
        width: 80%;
        padding: 0 30px;
        position: relative;
        z-index: 1;
    }

    .breadcrumb {
        font-size: 12px;
        color: #888;
        margin-bottom: 15px;
    }

    .store-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #222;
    }

    .store-subtitle {
        color: #777;
        margin-bottom: 25px;
    }

    .product-section-title {
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.08);
    }

    .card-img-top {
        height: 240px;
        object-fit: cover;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        width: 100%;
    }

    .card-body {
        padding: 15px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
        min-height: 44px;
    }

    .card-subtitle {
        font-size: 15px;
        color: #999;
        margin-bottom: 10px;
    }

    .card-text {
        font-size: 14px;
        color: #555;
        margin-bottom: 15px;
        flex: 1;
        min-height: 60px;
    }

    .card .btn-group {
        margin-top: auto;
    }

    .btn-primary, .btn-success {
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease, color 0.3s ease;
        margin-right: 8px;
    }

    .btn-primary {
        background-color: #000;
        color: #fff;
        border: 1px solid #444;
    }

    .btn-primary:hover {
        background-color: #222;
    }

    .btn-success {
        background-color: #b08e6b;
        color: #fff;
    }

    .btn-success:hover {
        background-color: #a07a50;
    }
</style>

<!-- MAIN CONTENT -->
<div class="fashion-container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4>Choose From</h4>
        <ul class="category-list">
            <li><a href="index.php" class="<?php echo !$selectedCategory ? 'active' : ''; ?>">All Products</a></li>
            <?php foreach($categories->getAll() as $category): ?>
                <li>
                    <a href="index.php?category=<?php echo urlencode($category['name']); ?>" 
                       class="<?php echo $selectedCategory === $category['name'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- PRODUCT LISTING -->
    <div class="main-content">
        <div class="store-title">Welcome to M&B CLOTHING STORE</div>

        <div class="product-section-title"><?php echo $selectedCategory ? $selectedCategory : 'Latest Products'; ?></div>
        <div class="row">
            <?php 
            $productList = $selectedCategory ? $products->getByCategory($selectedCategory) : $products->getAll();
            foreach ($productList as $product): 
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="<?php echo $product['image_path'] ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']) ?></h5>
                            <h6 class="card-subtitle"><?php echo $pesoFormatter->formatCurrency($product['price'], 'PHP') ?></h6>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']) ?></p>
                            <a href="product.php?id=<?php echo $product['id'] ?>" class="btn btn-primary">View Product</a>
                            <?php if(isset($_SESSION['user'])): ?>
                                <a href="#" class="btn btn-success add-to-cart" data-productid="<?php echo $product['id'] ?>" data-quantity="1">Add to Cart</a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-success">Login to Add to Cart</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>
