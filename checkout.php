<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Aries\MiniFrameworkStore\Models\Checkout;

$checkout = new Checkout();

$superTotal = 0;
$orderId = null;

if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
        $superTotal += $item['total'] * $item['quantity'];
    }
}

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    if(isset($_SESSION['user'])) {
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

    foreach($_SESSION['cart'] as $item) {
        $checkout->saveOrderDetails([
            'order_id' => $orderId,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'subtotal' => $item['total'] * $item['quantity']
        ]);
    }

    unset($_SESSION['cart']);
    header('Location: index.php');
    exit;
}

?>

<style>
    .checkout-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
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

    .checkout-section {
        background-color: #fff;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .checkout-section h2 {
        margin: 0 0 20px;
        font-size: 20px;
        color: #333;
    }

    .checkout-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
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

    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #b08e6b;
        outline: none;
        box-shadow: 0 0 0 2px rgba(176,142,107,0.2);
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

<div class="checkout-container">
    <div class="checkout-header">
        <h1>Checkout</h1>
    </div>

    <div class="checkout-section">
        <h2>Order Summary</h2>
        <?php if(countCart() > 0): ?>
            <table class="checkout-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?php echo $item['name'] ?></td>
                            <td><?php echo $item['quantity'] ?></td>
                            <td><?php echo $pesoFormatter->formatCurrency($item['price'], 'PHP') ?></td>
                            <td><?php echo $pesoFormatter->formatCurrency($item['total'], 'PHP') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td><strong><?php echo $pesoFormatter->formatCurrency($superTotal, 'PHP') ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if(countCart() > 0): ?>
        <div class="checkout-section">
            <h2>Shipping Information</h2>
            <form action="checkout.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <button type="submit" class="btn btn-success" name="submit">Place Order</button>
                <a href="cart.php" class="btn btn-primary">Back to Cart</a>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php template('footer.php'); ?>