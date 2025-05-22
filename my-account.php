<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Aries\MiniFrameworkStore\Models\User;
use Aries\MiniFrameworkStore\Models\Checkout;

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $birthdate = $_POST['birthdate'] ?? null;

    // Update user details in the database
    $userModel = new User();
    $userModel->update([
        'id' => $_SESSION['user']['id'],
        'name' => $name,
        'email' => $email,
        'address' => $address,
        'phone' => $phone,
        'birthdate' => Carbon\Carbon::createFromFormat('Y-m-d', $birthdate)->format('Y-m-d')
    ]);

    // Update session data
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['address'] = $address;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['birthdate'] = $birthdate;

    echo "<script>alert('Account details updated successfully!');</script>";
}

// Get user's order history
$checkout = new Checkout();
$userOrders = $checkout->getUserOrders($_SESSION['user']['id']);

$amountLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amountLocale, NumberFormatter::CURRENCY);

?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <h1>My Account</h1>
            <p>Welcome, <?php echo $_SESSION['user']['name']; ?></p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="col-md-8 bg-white p-5">
            <h2>Edit Account Details</h2>
            <form action="my-account.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $_SESSION['user']['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['user']['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $_SESSION['user']['address'] ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $_SESSION['user']['phone'] ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo $_SESSION['user']['birthdate'] ?? ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Order History Section -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="bg-white p-5">
                <h2>Order History</h2>
                <?php if (!empty($userOrders)): ?>
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
                    <div class="text-center py-4">
                        <p>You haven't placed any orders yet.</p>
                        <a href="index.php" class="btn btn-primary">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>