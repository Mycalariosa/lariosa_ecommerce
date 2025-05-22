<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<?php
use Aries\MiniFrameworkStore\Models\Checkout;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$orders = new Checkout();
$userOrders = $orders->getUserOrders($_SESSION['user']['id']);

$amountLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amountLocale, NumberFormatter::CURRENCY);
?>

<style>
    .account-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .account-header {
        background-color: #2e2e2e;
        color: #fff;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .account-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .account-header p {
        margin: 10px 0 0;
        color: #ddd;
    }

    .account-section {
        background-color: #fff;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .account-section h2 {
        margin: 0 0 20px;
        font-size: 20px;
        color: #333;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-table th,
    .order-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .order-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .order-table tr:hover {
        background-color: #f8f9fa;
    }

    .no-orders {
        text-align: center;
        padding: 30px;
        color: #666;
    }

    .btn-primary {
        background-color: #2e2e2e;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #1a1a1a;
    }
</style>

<div class="account-container">
    <div class="account-header">
        <h1>My Account</h1>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</p>
    </div>

    <div class="account-section">
        <h2>Account Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
    </div>

    <div class="account-section">
        <h2>Order History</h2>
        <?php if (!empty($userOrders)): ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Products</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userOrders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo $pesoFormatter->formatCurrency($order['total_price'], 'PHP'); ?></td>
                            <td>Completed</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="index.php" class="btn-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php template('footer.php'); ?> 