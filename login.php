<?php 
include 'helpers/functions.php'; 
template('header.php'); 

use Aries\MiniFrameworkStore\Models\User;

$user = new User();

session_start();

if (isset($_POST['submit'])) {
    $user_info = $user->login([
        'email' => $_POST['email'],
    ]);

    if ($user_info && password_verify($_POST['password'], $user_info['password'])) {
        $_SESSION['user'] = $user_info;

        if (isset($_POST['remember_me'])) {
            // Generate a random token
            $token = bin2hex(random_bytes(16));
            // Save the token in database for this user
            $user->saveRememberToken($user_info['id'], $token);

            // Set cookie for 30 days, HTTP only for security
            setcookie('rememberme', $token, time() + (86400 * 30), "/", "", false, true);
        } else {
            // If unchecked, clear cookie if exists
            if (isset($_COOKIE['rememberme'])) {
                setcookie('rememberme', '', time() - 3600, "/");
            }
            // Also clear token in DB
            $user->saveRememberToken($user_info['id'], null);
        }

        header('Location: index.php'); // redirect to homepage
        exit;
    } else {
        $message = 'Invalid email or password.';
    }
}

// Auto-login via cookie if session not set
if (!isset($_SESSION['user']) && isset($_COOKIE['rememberme'])) {
    $token = $_COOKIE['rememberme'];
    $user_info = $user->getUserByRememberToken($token);
    if ($user_info) {
        $_SESSION['user'] = $user_info;
        // Refresh cookie expiry
        setcookie('rememberme', $token, time() + (86400 * 30), "/", "", false, true);
        header('Location: index.php');
        exit;
    }
}

// Redirect already logged-in users
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: index.php');
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

    .form-check-label {
        color: #bbb;
    }

    .form-check-input {
        background-color: #121212;
        border: 1px solid #444;
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
        <div class="login-title">Login</div>

        <?php if (isset($message)): ?>
            <div class="error-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" required>
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input name="password" type="password" class="form-control" id="exampleInputPassword1" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="remember_me" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Remember me</label>
            </div>

            <button type="submit" name="submit" class="btn-outline-light">Login</button>

            <div class="extra-links">
                <small>Don't have an account? <a href="register.php" class="link-secondary">Register</a></small>
            </div>
        </form>
    </div>
</div>

<?php template('footer.php'); ?>
