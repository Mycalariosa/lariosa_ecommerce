<?php 
include 'helpers/functions.php'; 

// Include Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

// Correct namespace for the Product class
use App\Models\Product;

?>
<?php template('header.php'); ?>
<?php

$productId = $_GET['id'];

// Ensure $db is initialized
global $db;

// Pass $db to the Product class
$products = new Product($db);
$product = $products->getById($productId);

if (!$product) {
    // Redirect to index if the product is not found
    header('Location: index.php');
    exit();
}

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);

?>

<style>
    .product-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .product-header {
        background-color: #2e2e2e;
        color: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .product-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .product-content {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .product-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .product-details {
        padding: 0 20px;
    }

    .product-price {
        font-size: 24px;
        color: #b08e6b;
        font-weight: 600;
        margin: 15px 0;
    }

    .product-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .btn {
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 500;
    }

    .btn-success {
        background-color: #b08e6b;
    }

    .btn-success:hover {
        background-color: #a07a50;
    }

    .btn-primary {
        background-color: #2e2e2e;
    }

    .btn-primary:hover {
        background-color: #1a1a1a;
    }

    @media (min-width: 768px) {
        .product-content {
            display: flex;
            gap: 30px;
        }

        .product-image {
            width: 50%;
            margin-bottom: 0;
        }

        .product-details {
            width: 50%;
            padding: 0;
        }
    }
</style>

<div class="product-container">
    <div class="product-header">
        <h1>Product Details</h1>
    </div>

    <div class="product-content">
        <img src="<?php echo $product['image_path'] ?>" alt="<?php echo htmlspecialchars($product['name']) ?>" class="product-image">
        <div class="product-details">
            <h2><?php echo htmlspecialchars($product['name']) ?></h2>
            <div class="product-price"><?php echo $pesoFormatter->formatCurrency($product['price'], 'PHP') ?></div>
            <p class="product-description"><?php echo htmlspecialchars($product['description']) ?></p>
            <div class="product-actions">
                <a href="#" class="btn btn-success add-to-cart" data-productid="<?php echo $product['id'] ?>" data-quantity="1">Add to Cart</a>
                <a href="index.php" class="btn btn-primary">Back to Products</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addToCartButton = document.querySelector('.add-to-cart');

        if (addToCartButton) {
            addToCartButton.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default link behavior

                const productId = this.dataset.productid;
                const quantity = this.dataset.quantity;

                // Perform an AJAX request to add the product to the cart
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to the cart page
                        window.location.href = 'cart.php';
                    } else {
                        console.error('Error:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });
</script>

<?php template('footer.php'); ?>