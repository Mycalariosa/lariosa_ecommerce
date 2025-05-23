<?php
session_start();
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
            'role' => $user_info['role'],
            'profile_picture' => $user_info['profile_picture'] ?? 'assets/images/default-profile.png'
        ];
        setcookie('rememberme', $token, time() + (86400 * 30), "/", "", false, true);
        header('Location: dashboard.php');
        exit;
    }
}

// Redirect if already logged in
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Handle login form
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
                'role' => $user_info['role'],
                'profile_picture' => $user_info['profile_picture'] ?? 'assets/images/default-profile.png'
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

            header('Location: index.php');
            exit;
        } else {
            $message = 'Invalid email or password.';
        }
    }
}

template('header.php');
?>

<style>
  html, body {
    height: 100%;
    margin: 0;
    background-color: #121212;
    color: #e0e0e0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    flex-direction: column;
  }

  .main-content {
    flex: 1 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 15px;
  }

  .login-container {
    background-color: #1c1c1c;
    padding: 2rem 2.5rem;
    width: 350px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.7);
  }

  .login-title {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
  }

  label {
    display: block;
    margin-bottom: 0.3rem;
    color: #bbb;
    font-weight: 500;
  }

  input[type="email"],
  input[type="password"] {
    background-color: #121212;
    border: 1px solid #333;
    color: #e0e0e0;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 6px;
    width: 100%;
    font-size: 1rem;
  }

  input[type="email"]:focus,
  input[type="password"]:focus {
    outline: none;
    border-color: #555;
    background-color: #121212;
  }

  .form-check {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  .form-check input[type="checkbox"] {
    margin-right: 8px;
    accent-color: #007bff;
  }

  .form-check label {
    color: #bbb;
    margin: 0;
  }

  .btn-login {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
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
    background-color: rgba(255, 102, 102, 0.15);
    padding: 10px;
    margin-bottom: 1rem;
    border-radius: 6px;
    text-align: center;
    font-weight: 500;
  }

  .extra-links {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.9rem;
  }

  .extra-links a {
    color: #bbb;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .extra-links a:hover {
    color: #fff;
  }

  footer {
    flex-shrink: 0;
    background-color: #1c1c1c;
    color: #aaa;
    text-align: center;
    padding: 12px 0;
    font-size: 0.9rem;
  }
</style>

<div class="main-content">
  <div class="login-container">
    <div class="login-title">Login</div>

    <?php if (!empty($message)) : ?>
      <div class="error-message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" autocomplete="off">
      <label for="email">Email address</label>
      <input name="email" type="email" id="email" required
        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">

      <label for="password">Password</label>
      <input name="password" type="password" id="password" required>

      <div class="form-check">
        <input type="checkbox" name="remember_me" id="rememberMe" <?= isset($_POST['remember_me']) ? 'checked' : '' ?>>
        <label for="rememberMe">Remember me</label>
      </div>

      <button type="submit" name="submit" class="btn-login">Login</button>
    </form>

    <div class="extra-links">
      <small>Don't have an account? <a href="register.php">Register</a></small>
    </div>
  </div>
</div>

<?php template('footer.php'); ?>
