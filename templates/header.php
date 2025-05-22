<?php

require 'vendor/autoload.php';

use Aries\MiniFrameworkStore\Models\Category;

$categories = new Category();

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f7f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header-bar {
            background-color: #2e2e2e;
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .store-logo {
            display: flex;
            align-items: center;
            font-family: 'Georgia', serif;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .store-logo img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
        }

        .store-logo span {
            color: #b08e6b;
        }

        .header-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-links a:hover {
            color: #fff;
        }

        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #2e2e2e;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 6px;
        }
        
        .user-dropdown-content a {
            color: #ddd;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease;
        }
        
        .user-dropdown-content a:hover {
            background-color: #3e3e3e;
            color: #fff;
        }
        
        .user-dropdown:hover .user-dropdown-content {
            display: block;
        }
        
        .user-dropdown:hover .user-name {
            background-color: #3e3e3e;
        }
        
        .user-name {
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            color: #ddd;
        }

        .btn-outline-light {
            background-color: transparent;
            border: 1px solid #ddd;
            color: #ddd;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: #ddd;
            color: #2e2e2e;
        }

        .badge {
            background-color: #b08e6b !important;
            color: #fff;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header-bar">
        <div class="store-logo">
            <img src="assets/images/logo_1.png" alt="M&B Logo">
            M&B <span>CLOTHING STORE</span>
        </div>
        <div class="header-links">
            <a href="index.php">Home</a>
            <a href="add-product.php">Add Product</a>
            <a href="cart.php">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" version="1.1" id="Capa_1" width="20px" height="20px" viewBox="0 0 902.86 902.86" xml:space="preserve">
                    <g>
                        <g>
                            <path d="M671.504,577.829l110.485-432.609H902.86v-68H729.174L703.128,179.2L0,178.697l74.753,399.129h596.751V577.829z     M685.766,247.188l-67.077,262.64H131.199L81.928,246.756L685.766,247.188z"/>
                            <path d="M578.418,825.641c59.961,0,108.743-48.783,108.743-108.744s-48.782-108.742-108.743-108.742H168.717    c-59.961,0-108.744,48.781-108.744,108.742s48.782,108.744,108.744,108.744c59.962,0,108.743-48.783,108.743-108.744    c0-14.4-2.821-28.152-7.927-40.742h208.069c-5.107,12.59-7.928,26.342-7.928,40.742    C469.675,776.858,518.457,825.641,578.418,825.641z M209.46,716.897c0,22.467-18.277,40.744-40.743,40.744    c-22.466,0-40.744-18.277-40.744-40.744c0-22.465,18.277-40.742,40.744-40.742C191.183,676.155,209.46,694.432,209.46,716.897z     M619.162,716.897c0,22.467-18.277,40.744-40.743,40.744s-40.743-18.277-40.743-40.744c0-22.465,18.277-40.742,40.743-40.742    S619.162,694.432,619.162,716.897z"/>
                        </g>
                    </g>
                </svg>
                <span class="badge"><?php echo countCart(); ?></span>
            </a>
            <?php if(isset($_SESSION['user'])): ?>
                <div class="user-dropdown">
                    <div class="user-name">
                        Hello, <?php echo $_SESSION['user']['name']; ?>
                    </div>
                    <div class="user-dropdown-content">
                        <a href="my-account.php">My Account</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
