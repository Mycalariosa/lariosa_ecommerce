<?php 
include 'helpers/functions.php'; 
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Checkout;

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
    /* ...existing styles... */
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