<?php 
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'helpers/functions.php'; 

// Check if user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

use App\Models\Checkout;
use App\Models\User;

$checkout = new Checkout();
$user = new User();

// Get user's orders
$userOrders = $checkout->getUserOrders($_SESSION['user']['id']);

// Get user's information
$userInfo = $_SESSION['user'];

template('header.php'); ?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f7f5;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: auto;
        padding: 40px 20px;
    }

    .dashboard-header {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        text-align: center;
    }

    .welcome-message {
        font-size: 28px;
        font-weight: bold;
        color: #222;
        margin-bottom: 10px;
    }

    .dashboard-subtitle {
        color: #777;
        font-size: 16px;
    }

    .stats-row {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stats-card {
        flex: 1;
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.08);
    }

    .stats-number {
        font-size: 32px;
        font-weight: bold;
        color: #b08e6b;
        margin-bottom: 10px;
    }

    .stats-label {
        color: #666;
        font-size: 16px;
        font-weight: 500;
    }

    .dashboard-card {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .dashboard-card h3 {
        font-size: 20px;
        font-weight: 600;
        color: #222;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background-color: #f8f7f5;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #444;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .quick-links {
        display: flex;
        gap: 20px;
    }

    .quick-links .dashboard-card {
        flex: 1;
    }

    .list-group-item {
        padding: 12px 15px;
        border: none;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s ease;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item:hover {
        background-color: #f8f7f5;
    }

    .list-group-item i {
        margin-right: 10px;
        color: #b08e6b;
    }

    .btn-outline-primary {
        color: #b08e6b;
        border-color: #b08e6b;
        padding: 8px 20px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #b08e6b;
        color: #fff;
    }
</style>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="dashboard-header">
        <h1 class="welcome-message">Welcome back, <?php echo htmlspecialchars($userInfo['name']); ?>!</h1>
        <p class="dashboard-subtitle">Here's an overview of your account</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stats-card">
            <div class="stats-number"><?php echo count($userOrders); ?></div>
            <div class="stats-label">Total Orders</div>
        </div>
        <div class="stats-card">
            <div class="stats-number">₱<?php 
                $totalSpent = array_sum(array_column($userOrders, 'total_price'));
                echo number_format($totalSpent, 2);
            ?></div>
            <div class="stats-label">Total Spent</div>
        </div>
        <div class="stats-card">
            <div class="stats-number"><?php 
                $lastOrder = end($userOrders);
                echo $lastOrder ? date('M d', strtotime($lastOrder['order_date'])) : 'N/A';
            ?></div>
            <div class="stats-label">Last Order</div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="dashboard-card">
        <h3>Recent Orders</h3>
        <?php if (!empty($userOrders)): ?>
            <div class="table-responsive">
                <table class="table">
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
                        <?php foreach (array_slice($userOrders, 0, 5) as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td>₱<?php echo number_format($order['total_price'], 2); ?></td>
                                <td><span class="badge badge-success">Completed</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (count($userOrders) > 5): ?>
                <div class="text-center mt-3">
                    <a href="my-account.php" class="btn btn-outline-primary">View All Orders</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-4">
                <p>You haven't placed any orders yet.</p>
                <a href="index.php" class="btn btn-outline-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div class="quick-links">
        <div class="dashboard-card">
            <h3>Account Settings</h3>
            <div class="list-group">
                <a href="my-account.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-user"></i> Edit Profile
                </a>
                <a href="change-password.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-key"></i> Change Password
                </a>
            </div>
        </div>
        <div class="dashboard-card">
            <h3>Quick Actions</h3>
            <div class="list-group">
                <a href="index.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-cart"></i> Continue Shopping
                </a>
                <a href="cart.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag"></i> View Cart
                </a>
            </div>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>