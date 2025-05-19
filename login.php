<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<?php

use Aries\MiniFrameworkStore\Models\User;

$user = new User();

if (isset($_POST['submit'])) {
    $user_info = $user->login([
        'email' => $_POST['email'],
    ]);

    if ($user_info && password_verify($_POST['password'], $user_info['password'])) {
        $_SESSION['user'] = $user_info;
        header('Location: my-account.php');
        exit;
    } else {
        $message = 'Invalid username or password';
    }
}

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: my-account.php');
    exit;
}
?>

<style>
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 75vh; /* allows room for navbar and footer */
    }

    .login-container {
        background-color: #fff;
        padding: 40px;
        width: 100%;
        max-width: 400px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .login-container h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #000;
    }

    .form-label {
        font-weight: bold;
        color: #000;
    }

    .form-control {
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .form-check-label {
        color: #000;
    }

    .btn-black {
        width: 100%;
        padding: 12px;
        background-color: #000;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        transition: background-color 0.3s ease;
    }

    .btn-black:hover {
        background-color: #333;
    }

    .error-message {
        color: red;
        text-align: center;
        margin-bottom: 20px;
    }
</style>

<div class="login-wrapper">
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($message)): ?>
            <div class="error-message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" required>

            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1" required>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Remember me</label>
            </div>

            <button type="submit" name="submit" class="btn-black">Login</button>
        </form>
    </div>
</div>

<?php template('footer.php'); ?>
