<?php
session_start(); // Always start the session before any output
include 'helpers/functions.php';
require_once 'helpers/role_helper.php';

use App\Models\User;

$user = new User();
$message = null;

// Auto-login via rememberme token
if (!isset($_SESSION['user']) && isset($_COOKIE['rememberme'])) {
    $token = $_COOKIE['rememberme'];
    $user_info = $user->getUserByRememberToken($token);
    if ($user_info) {
        $_SESSION['user'] = [
            'id' => $user_info['id'],
            'name' => $user_info['name'],
            'email' => $user_info['email'],
            'role' => $user_info['role']
        ];
        setcookie('rememberme', $token, time() + (86400 * 30), "/", "", false, true);
        header('Location: dashboard.php');
        exit;
    }
}

// Redirect if already logged in
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = 'Please enter both email and password.';
    } else {
        $user_info = $user->login([
            'email' => $email,
            'password' => $password
        ]);

        if ($user_info) {
            $_SESSION['user'] = [
                'id' => $user_info['id'],
                'name' => $user_info['name'],
                'email' => $user_info['email'],
                'role' => $user_info['role']
            ];

            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(16));
                $user->saveRememberToken($user_info['id'], $token);
                setcookie('rememberme', $token, time() + (86400 * 30), "/", "", false, true);
            } else {
                if (isset($_COOKIE['rememberme'])) {
                    setcookie('rememberme', '', time() - 3600, "/");
                }
                $user->saveRememberToken($user_info['id'], null);
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $message = 'Invalid email or password.';
        }
    }
}

// Check if user is admin
if (isAdmin()) {
    // Show admin features
}

// Require admin access
requireAdmin();

// Check if user is customer
if (isCustomer()) {
    // Show customer features
}

// Get user role
$role = getUserRole();

template('header.php');
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
        width: 100%;
    }

    .form-control:focus {
        background-color: #121212;
        color: #ffffff;
        border-color: #555;
        box-shadow: none;
        outline: none;
    }

    .form-check-label {
        color: #bbb;
    }

    .form-check-input {
        background-color: #121212;
        border: 1px solid #444;
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-login:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .error-message {
        color: #ff6666;
        text-align: center;
        margin-bottom: 1rem;
        padding: 10px;
        background-color: rgba(255, 102, 102, 0.1);
        border-radius: 6px;
    }

    .link-secondary {
        color: #bbbbbb;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .link-secondary:hover {
        color: #ffffff;
    }

    .extra-links {
        text-align: center;
        margin-top: 1.5rem;
    }
</style>

<div class="login-wrapper">
    <div class="login-container">
        <div class="login-title">Login</div>

        <?php if (!empty($message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name="email" type="email" class="form-control" id="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="password" type="password" class="form-control" id="password" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="remember_me" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>

            <button type="submit" name="submit" class="btn-login">Login</button>

            <div class="extra-links">
                <small>Don't have an account? <a href="register.php" class="link-secondary">Register</a></small>
            </div>
        </form>
    </div>
</div>

<?php template('footer.php'); ?>
