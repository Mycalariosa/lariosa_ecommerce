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

    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 30px 15px;
    }

    .login-container {
        background-color: #1e1e1e;
        padding: 2.5rem;
        width: 100%;
        max-width: 400px;
        border-radius: 12px;
        box-shadow: 0 0 10px #00000099;
    }

    .login-title {
        font-size: 1.8rem;
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .form-label {
        color: #ccc;
        font-weight: 500;
    }

    .form-control {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #333;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 6px;
    }

    .form-control:focus {
        background-color: #121212;
        color: #ffffff;
        border-color: #555;
        box-shadow: none;
    }

    .btn-outline-light {
        width: 100%;
        padding: 12px;
        background-color: #000;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        transition: background-color 0.3s ease;
    }

    .btn-outline-light:hover {
        background-color: #333;
    }

    .error-message {
        color: #ff6666;
        text-align: center;
        margin-bottom: 1rem;
    }

    .link-secondary {
        color: #bbbbbb;
        text-decoration: none;
    }

    .link-secondary:hover {
        color: #ffffff;
    }

    .extra-links {
        text-align: center;
        margin-top: 1rem;
    }
</style>

<div class="login-wrapper">
    <div class="login-container">
        <div class="login-title">Register</div>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
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
            <button type="submit" name="submit" class="btn-outline-light">Register</button>
            <div class="extra-links">
                <small>Already have an account? <a href="login.php" class="link-secondary">Log in</a></small>
            </div>
        </form>
    </div>
</div>

<?php template('footer.php'); ?>
