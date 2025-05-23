<?php 
include 'helpers/functions.php'; 
require_once __DIR__ . '/vendor/autoload.php';

session_start(); // Ensure session is started

if (isset($_GET['remove'])) {
    $productId = filter_input(INPUT_GET, 'remove', FILTER_SANITIZE_NUMBER_INT);
    if ($productId && isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]); // Remove the specific product from the cart
    }
    header('Location: cart.php'); // Redirect to refresh the cart page
    exit;
}

$amountLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amountLocale, NumberFormatter::CURRENCY);

?>
<?php template('header.php'); ?>

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

    .cart-container {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: 1rem;
        position: relative;
        z-index: 1;
    }

    .cart-header {
        background-color: #2e2e2e;
        color: #fff;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .cart-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .cart-table {
        background-color: #fff;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .cart-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table th,
    .cart-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .cart-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .cart-table tr:hover {
        background-color: #f8f9fa;
    }

    .btn {
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-right: 10px;
        color: #fff;
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

    .empty-cart {
        text-align: center;
        padding: 40px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .empty-cart p {
        color: #666;
        margin-bottom: 20px;
    }
</style>

<div class="cart-container">
    <div class="cart-header">
        <h1>Shopping Cart</h1>
    </div>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <p>No items in your cart.</p>
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $superTotal = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $pesoFormatter->formatCurrency($item['price'], 'PHP'); ?></td>
                            <td><?php echo $pesoFormatter->formatCurrency($item['total'], 'PHP'); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $productId; ?>" class="btn btn-primary">Remove</a>
                            </td>
                            <?php $superTotal += $item['total']; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td colspan="2"><strong><?php echo $pesoFormatter->formatCurrency($superTotal, 'PHP'); ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4">
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php template('footer.php'); ?>