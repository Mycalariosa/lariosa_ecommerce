<?php 
include 'helpers/functions.php'; 
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Checkout;

session_start(); // Ensure session is started

$checkout = new Checkout();

$superTotal = 0;
$orderId = null;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $superTotal += $item['total'] * $item['quantity']; // Calculate total
    }
}

$amountLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amountLocale, NumberFormatter::CURRENCY);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

    if (!empty($_SESSION['cart'])) {
        if (isset($_SESSION['user'])) {
            $orderId = $checkout->userCheckout([
                'user_id' => $_SESSION['user']['id'],
                'total' => $superTotal
            ]);
        } else {
            $orderId = $checkout->guestCheckout([
                'name' => $name,
                'address' => $address,
                'phone' => $phone,
                'total' => $superTotal
            ]);
        }

        foreach ($_SESSION['cart'] as $item) {
            $checkout->saveOrderDetails([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['total'] * $item['quantity']
            ]);
        }

        unset($_SESSION['cart']); // Clear the cart after checkout
        header('Location: success.php'); // Redirect to success page
        exit;
    }
}
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

    .checkout-container {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: 1rem;
        position: relative;
        z-index: 1;
    }

    .checkout-header {
        background-color: #2e2e2e;
        color: #fff;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .checkout-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .checkout-table {
        background-color: #fff;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .checkout-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .checkout-table th,
    .checkout-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .checkout-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .checkout-table tr:hover {
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

    .checkout-section {
        margin-top: 30px;
    }

    .checkout-section h2 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .text-end {
        text-align: right;
    }

    .payment-method {
        margin-top: 20px;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .payment-method span {
        color: #b08e6b;
    }
</style>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>Checkout</h1>
    </div>

    <div class="checkout-section">
        <h2>Order Summary</h2>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="checkout-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo $pesoFormatter->formatCurrency($item['price'], 'PHP'); ?></td>
                                <td><?php echo $pesoFormatter->formatCurrency($item['total'] * $item['quantity'], 'PHP'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td><strong><?php echo $pesoFormatter->formatCurrency($superTotal, 'PHP'); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="checkout-section">
            <h2>Shipping Information</h2>
            <form action="checkout.php" method="POST">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>

                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>

                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>

                <div class="payment-method">
                    Payment Method: <span>Cash Only</span>
                </div>

                <button type="submit" class="btn btn-success" name="submit">Place Order</button>
                <a href="cart.php" class="btn btn-primary">Back to Cart</a>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php template('footer.php'); ?>