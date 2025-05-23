<?php
session_start();
include 'helpers/functions.php';

use App\Models\User;
use App\Models\Checkout;
use Carbon\Carbon;

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$userModel = new User();

// 1️⃣ Handle profile‐picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file       = $_FILES['profile_picture'];
    $userId     = $_SESSION['user']['id'];
    $allowed    = ['image/jpeg','image/png','image/gif'];
    $maxSize    = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed)) {
        $_SESSION['error'] = 'Only JPG, PNG or GIF allowed.';
    }
    elseif ($file['size'] > $maxSize) {
        $_SESSION['error'] = 'Max file size is 5MB.';
    }
    else {
        // Build destination
        $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename   = "profile_{$userId}_" . time() . ".{$ext}";
        $uploadDir  = 'assets/images/profile_pictures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $dest       = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            // Update DB & session
            $userModel->update([
                'id'              => $userId,
                'profile_picture' => $dest
            ]);
            $_SESSION['user']['profile_picture'] = $dest;
        } else {
            $_SESSION['error'] = 'Upload failed, please try again.';
        }
    }

    header('Location: my-account.php');
    exit();
}

// 2️⃣ Handle account‐detail edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $userId    = $_SESSION['user']['id'];
    $name      = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $address   = trim($_POST['address'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $birthdate = $_POST['birthdate'] ?? null;

    // optional: additional validation here

    $userModel->update([
        'id'        => $userId,
        'name'      => $name,
        'email'     => $email,
        'address'   => $address,
        'phone'     => $phone,
        'birthdate' => $birthdate
            ? Carbon::createFromFormat('Y-m-d', $birthdate)->format('Y-m-d')
            : null
    ]);

    // Refresh session values
    $_SESSION['user']['name']      = $name;
    $_SESSION['user']['email']     = $email;
    $_SESSION['user']['address']   = $address;
    $_SESSION['user']['phone']     = $phone;
    $_SESSION['user']['birthdate'] = $birthdate;

    echo "<script>alert('Account details updated successfully!');</script>";
}

// 3️⃣ Fetch order history
$checkout    = new Checkout();
$userOrders  = $checkout->getUserOrders($_SESSION['user']['id']);
$pesoFmt     = new NumberFormatter('en_PH', NumberFormatter::CURRENCY);
$profilePic  = $_SESSION['user']['profile_picture'] 
              ?? 'assets/images/default-profile.png';
?>
<?php template('header.php'); ?>
<div class="container my-5">
    <div class="row">
        <!-- PROFILE & UPLOAD -->
        <div class="col-md-4">
            <h1>My Account</h1>
            <p>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="profile-picture-section mt-4 text-center">
                <img src="<?= htmlspecialchars($profilePic) ?>"
                     class="img-thumbnail rounded-circle"
                     style="width:150px;height:150px;object-fit:cover;"
                     alt="Profile Picture">
                <form method="POST" enctype="multipart/form-data" class="mt-2">
                    <label class="btn btn-dark btn-sm mb-0">
                        Change Picture
                        <input type="file"
                               name="profile_picture"
                               accept="image/*"
                               hidden
                               onchange="this.form.submit()">
                    </label>
                </form>
            </div>
        </div>

        <!-- ACCOUNT DETAILS FORM -->
        <div class="col-md-8 bg-white p-5">
            <h2>Account Details</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control"
                           required
                           value="<?= htmlspecialchars($_SESSION['user']['name']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           required
                           value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input name="address" class="form-control"
                           value="<?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input name="phone" class="form-control"
                           value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Birthdate</label>
                    <input type="date"
                           name="birthdate"
                           class="form-control"
                           value="<?= htmlspecialchars($_SESSION['user']['birthdate'] ?? '') ?>">
                </div>
                <button name="submit" class="btn btn-dark">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- ORDER HISTORY -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="bg-white p-4">
                <h2>Order History</h2>
                <?php if ($userOrders): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Products</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($userOrders as $o): ?>
                            <tr>
                                <td>#<?= $o['id'] ?></td>
                                <td><?= date('M d, Y',strtotime($o['order_date'])) ?></td>
                                <td><?= htmlspecialchars($o['product_name']) ?></td>
                                <td><?= $pesoFmt->formatCurrency($o['total_price'],'PHP') ?></td>
                                <td>Completed</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p>No orders yet.</p>
                        <a href="index.php" class="btn btn-dark">Shop Now</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php template('footer.php'); ?>
