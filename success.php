<?php
session_start();
include 'helpers/functions.php'; // Ensure the template() function is available
?>
<?php template('header.php', ['hide_buttons' => true]); ?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f7f5;
        color: #333;
        margin: 0;
        padding: 0;
        padding-top: 80px; /* Add padding for fixed header */
    }

    .container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1; /* Ensure content is beneath the fixed header (z-index: 1000) */
    }

    .success-container {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        margin-top: 1rem;
        text-align: center;
    }

    .success-header {
        background-color: #2e2e2e;
        color: #fff;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .success-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .success-message {
        font-size: 18px;
        color: #666;
        margin-bottom: 20px;
    }

    .btn {
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-right: 10px;
        color: #fff;
    }

    .btn-primary {
        background-color: #2e2e2e;
    }

    .btn-primary:hover {
        background-color: #1a1a1a;
    }
</style>

<div class="container">
    <div class="success-container">
        <div class="success-header">
            <h1>Order Success</h1>
        </div>
        <p class="success-message">Thank you for your order! Your order has been successfully placed.</p>
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</div>

<?php template('footer.php'); ?>
