<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php'; // adjust if needed

use App\Models\User;
use Carbon\Carbon;

// Redirect logged-in users
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$user = new User();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full-name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // You might want to add a dedicated existsByEmail method in User class for this
    $existingUser = $user->login(['email' => $email, 'password' => '']); // crude check, consider improving

    if ($existingUser) {
        $errors[] = 'Email is already registered.';
    } else {
        $registered = $user->register([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => Carbon::now('Asia/Manila')->toDateTimeString(),
            'updated_at' => Carbon::now('Asia/Manila')->toDateTimeString()
        ]);

        if ($registered) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}

include 'helpers/functions.php';
template('header.php');
?>

<style>
    body {
        background-color: #121212;
        color: #ffffff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Main container holds content and footer */
    main {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px 15px;
    }

    .register-container {
        background-color: #1e1e1e;
        padding: 2.5rem;
        width: 100%;
        max-width: 400px;
        border-radius: 12px;
        box-shadow: 0 0 10px #00000099;
    }

    .register-title {
        font-size: 1.8rem;
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    label {
        color: #ccc;
        font-weight: 500;
        display: block;
        margin-bottom: 6px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #333;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 6px;
        width: 100%;
        box-sizing: border-box;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #555;
        outline: none;
    }

    .btn-outline-light {
        width: 100%;
        padding: 12px;
        background-color: #000;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        cursor: pointer;
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

    .extra-links {
        text-align: center;
        margin-top: 1rem;
    }

    .extra-links a {
        color: #bbbbbb;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .extra-links a:hover {
        color: #ffffff;
    }
</style>

<main>
    <div class="register-container">
        <div class="register-title">Register</div>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <label for="full-name">Name</label>
            <input id="full-name" name="full-name" type="text" required autocomplete="name" />

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" required autocomplete="email" />

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" />

            <button type="submit" class="btn-outline-light">Register</button>
        </form>

        <div class="extra-links">
            <small>Already have an account? <a href="login.php">Log in</a></small>
        </div>
    </div>
</main>

<?php template('footer.php'); ?>
