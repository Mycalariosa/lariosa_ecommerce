<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<?php

use Aries\MiniFrameworkStore\Models\User;
use Carbon\Carbon;

$user = new User();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full-name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email already exists
    if ($user->where('email', $email)->exists()) {
        $errors[] = 'Email is already registered.';
    } else {
        $registered = $user->register([
            'name' => $name,
            'email' => $email,
            'password' => $password, // Password will be hashed in the User model
            'role' => User::ROLE_CUSTOMER, // Default role for new registrations
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila')
        ]);

        if ($registered) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}

// Redirect logged-in users
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<style>
    body {
        background-color: #121212;
        color: #ffffff;
        font-family: 'Segoe UI', sans-serif;
    }
    .form-container {
        background-color: #1e1e1e;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 0 10px #00000099;
    }
    .form-control {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #333;
    }
    .form-control:focus {
        background-color: #121212;
        color: #ffffff;
        border-color: #555;
        box-shadow: none;
    }
    .btn-outline-light:hover {
        background-color: #ffffff;
        color: #000000;
    }
    .link-secondary {
        color: #bbbbbb;
    }
    .link-secondary:hover {
        color: #ffffff;
    }
    .form-title {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        text-align: center;
        font-weight: 600;
    }
</style>

<div class="container text-light">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-5 mb-5">

            <div class="form-container">

                <div class="form-title">Register</div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="full-name" class="form-label">Name</label>
                        <input name="full-name" type="text" class="form-control" id="full-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input name="email" type="email" class="form-control" id="exampleInputEmail1" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" id="exampleInputPassword1" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-outline-light w-100 mb-3">Register</button>
                    <div class="text-center">
                        <small>Already have an account? <a href="login.php" class="link-secondary">Log in</a></small>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<?php template('footer.php'); ?>
