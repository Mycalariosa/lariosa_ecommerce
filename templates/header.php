<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php';
require_once 'helpers/role_helper.php';

use App\Models\Category;

$categories = new Category();

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
            padding-top: 80px; /* Add padding to account for fixed header */
        }

        .header-bar {
            background-color: #2e2e2e;
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .store-logo {
            display: flex;
            align-items: center;
            font-family: 'Georgia', serif;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1.5px;
            z-index: 1001;
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
            z-index: 1001;
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
            z-index: 1002;
        }
        
        .user-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #2e2e2e;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1002;
            border-radius: 6px;
            margin-top: 5px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .user-dropdown-content.show {
            display: block;
            opacity: 1;
            visibility: visible;
        }
        
        .user-dropdown-content a {
            color: #ddd;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }
        
        .user-dropdown-content a:hover {
            background-color: #3e3e3e;
            color: #fff;
        }
        
        .user-name {
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            color: #ddd;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-name.active {
            background-color: #3e3e3e;
        }

        .user-name i {
            color: #b08e6b;
        }

        .user-dropdown-content a i {
            color: #b08e6b;
            width: 20px;
            text-align: center;
        }

        .user-dropdown-content a.logout {
            color: #ff6b6b;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 8px;
            padding-top: 12px;
        }

        .user-dropdown-content a.logout:hover {
            background-color: rgba(255, 107, 107, 0.1);
        }

        .user-dropdown-content a.logout i {
            color: #ff6b6b;
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

        .cart-icon {
            position: relative;
        }

        .cart-icon .badge {
            position: absolute;
            top: -8px;
            right: -8px;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header-bar">
        <div class="store-logo">
            <img src="assets/images/logo_1.png" alt="M&B Logo"> <!-- Add logo -->
            M&B <span>CLOTHING STORE</span>
        </div>
        <div class="header-links">
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['user'])): ?>
                <?php if(isAdmin()): ?>
                    <a href="product-management.php">Product Management</a> <!-- Updated link -->
                <?php endif; ?>
                <a href="cart.php" class="cart-icon">
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
                <div class="user-dropdown">
                    <div class="user-name">
                        <i class="fas fa-user-circle"></i>
                        <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                    </div>
                    <div class="user-dropdown-content">
                        <a href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <a href="my-account.php">
                            <i class="fas fa-user-cog"></i>
                            My Account
                        </a>
                        <a href="logout.php" class="logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdown = document.querySelector('.user-dropdown');
            const userDropdownContent = document.querySelector('.user-dropdown-content');
            const userName = document.querySelector('.user-name');

            // Toggle dropdown on click
            userName.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownContent.classList.toggle('show');
                userName.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target)) {
                    userDropdownContent.classList.remove('show');
                    userName.classList.remove('active');
                }
            });

            // Prevent dropdown from closing when clicking inside it
            userDropdownContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>
