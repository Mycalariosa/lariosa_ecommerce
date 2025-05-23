-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 04:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `admin_users`
-- (See below for the actual view)
--
CREATE TABLE `admin_users` (
`id` int(11)
,`name` varchar(255)
,`email` varchar(255)
,`password` varchar(255)
,`address` varchar(255)
,`phone` varchar(20)
,`birthdate` date
,`created_at` timestamp
,`updated_at` timestamp
,`remember_token` varchar(255)
,`profile_picture` varchar(255)
,`role` enum('admin','customer')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `customer_users`
-- (See below for the actual view)
--
CREATE TABLE `customer_users` (
`id` int(11)
,`name` varchar(255)
,`email` varchar(255)
,`password` varchar(255)
,`address` varchar(255)
,`phone` varchar(20)
,`birthdate` date
,`created_at` timestamp
,`updated_at` timestamp
,`remember_token` varchar(255)
,`profile_picture` varchar(255)
,`role` enum('admin','customer')
);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `guest_address` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Pending','Completed') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `guest_name`, `guest_phone`, `guest_address`, `total`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, NULL, NULL, NULL, 1200.00, '2025-05-23 11:32:05', '2025-05-23 11:32:05', 'Pending'),
(2, NULL, 'Guest', '0000000000', 'Unknown', 1200.00, '2025-05-23 12:44:02', '2025-05-23 12:44:02', 'Pending'),
(3, NULL, 'Guest', '0000000000', 'Unknown', 1200.00, '2025-05-23 12:46:26', '2025-05-23 12:46:26', 'Pending'),
(4, 4, NULL, NULL, NULL, 600.00, '2025-05-23 13:51:08', '2025-05-23 13:54:01', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 1, 1, 1200.00, 1200.00),
(2, 2, 1, 1, 1200.00, 1200.00),
(3, 3, 1, 1, 1200.00, 1200.00),
(4, 4, 7, 1, 600.00, 600.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `slug`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'Enchanted Ball Gown', 'glittery pink gown is a dress, typically evening wear, that features a pink fabric with glitter or other sparkly embellishments', 1200.00, 'enchanted-ball-gown', 'uploads/evening gown.jpg', '2025-05-22 09:41:42', '2025-05-23 12:59:09'),
(2, 4, 'Sports Strappy tops', '2-packs sleeveless that comes Dark beige/Black in color', 600.00, 'sports-strappy-tops', 'uploads/sleeves.jpg', '2025-05-22 09:58:49', '2025-05-22 03:58:49'),
(7, 8, 'pink jewel set (Fashion)', 'a variety of jewelry items, typically featuring pink gemstones, colors, or embellishments. These sets can include necklaces, earrings.', 600.00, 'pink-jewel-set', 'uploads/pink jewel.JPG', '2025-05-23 10:54:04', '2025-05-23 14:24:14'),
(8, 10, 'Batman Custome', 'a long-sleeve gray jumpsuit adorned with the legendary Batman logo, complemented by attached black gauntlets, boot-tops, a lightweight black cape', 500.00, 'batman-custome', 'uploads/batman.png', '2025-05-23 13:56:12', '2025-05-23 07:56:12'),
(9, 4, 'White Suit', ' a formal or professional ensemble typically consisting of a jacket and trousers, both crafted from white fabric', 800.00, 'white-suit', 'uploads/suit.png', '2025-05-23 13:56:54', '2025-05-23 07:56:54'),
(10, 9, 'Cargo Pants', 'typically a versatile and practical piece of clothing characterized by their ample storage pockets and durable fabric. ', 800.00, 'cargo-pants', 'uploads/cargo.jpg', '2025-05-23 14:20:51', '2025-05-23 08:20:51'),
(11, 8, 'Gold Earings', 'Gold earrings featuring diamond stones are a popular and elegant jewelry choice, available in various styles from classic studs to elaborate drops. ', 5000.00, 'gold-earings', 'uploads/earings.JPG', '2025-05-23 14:23:52', '2025-05-23 08:23:52'),
(12, 1, 'Wedding Gown ', 'A beautiful wedding gown, often made with materials like satin, silk, tulle, lace, and organza, can be described as a luxurious and elegant choice for a bride. ', 2300.00, 'wedding-gown-', 'uploads/wedding gown.png', '2025-05-23 14:26:38', '2025-05-23 08:26:38'),
(13, 10, 'Stitch (baby)', 'typically features a plush, blue jumpsuit with Stitch\'s signature features like his tummy and spot markings', 400.00, 'stitch-(baby)', 'uploads/stitch.jpg', '2025-05-23 14:28:48', '2025-05-23 08:28:48'),
(14, 9, 'Summer Shorts', 'de from breathable and lightweight materials like cotton, linen, or blends. They often have an elastic or buttoned waistband and may include pockets.', 600.00, 'summer-shorts', 'uploads/shorts.jpg', '2025-05-23 14:31:27', '2025-05-23 08:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`) VALUES
(8, 'Accessories'),
(9, 'Bottoms'),
(10, 'Costumes'),
(1, 'Dresses'),
(4, 'Tops');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remember_token` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'assets/images/default-profile.png',
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`, `birthdate`, `created_at`, `updated_at`, `remember_token`, `profile_picture`, `role`) VALUES
(4, 'Myca Lariosa', 'Myca.lariosa12@gmail.com', '$2y$10$JjAxJHRwf1MAcNSrE4q7CeX7BqvRDfukLR2w61uVrpc5yR8OW941e', 'Vito Minglanilla Cebu', '09999221439', '1999-09-10', '2025-05-22 07:34:36', '2025-05-23 08:22:11', NULL, 'assets/images/profile_pictures/profile_4_1747988531.png', 'admin'),
(6, 'Bushsellote', 'Bushsellote15@gmail.com', '$2y$10$J/dBi2TbNS/cc8Oa7rtRT.tElqU7xyZzf/JS8QAu8VTbLdDccV616', NULL, NULL, NULL, '2025-05-23 07:52:41', '2025-05-23 08:14:07', NULL, 'assets/images/profile_pictures/profile_6_1747988047.png', 'customer');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `before_user_insert` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.role NOT IN ('admin', 'customer') THEN
        SET NEW.role = 'customer';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_user_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF NEW.role NOT IN ('admin', 'customer') THEN
        SET NEW.role = 'customer';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `admin_users`
--
DROP TABLE IF EXISTS `admin_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_users`  AS SELECT `users`.`id` AS `id`, `users`.`name` AS `name`, `users`.`email` AS `email`, `users`.`password` AS `password`, `users`.`address` AS `address`, `users`.`phone` AS `phone`, `users`.`birthdate` AS `birthdate`, `users`.`created_at` AS `created_at`, `users`.`updated_at` AS `updated_at`, `users`.`remember_token` AS `remember_token`, `users`.`profile_picture` AS `profile_picture`, `users`.`role` AS `role` FROM `users` WHERE `users`.`role` = 'admin' ;

-- --------------------------------------------------------

--
-- Structure for view `customer_users`
--
DROP TABLE IF EXISTS `customer_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `customer_users`  AS SELECT `users`.`id` AS `id`, `users`.`name` AS `name`, `users`.`email` AS `email`, `users`.`password` AS `password`, `users`.`address` AS `address`, `users`.`phone` AS `phone`, `users`.`birthdate` AS `birthdate`, `users`.`created_at` AS `created_at`, `users`.`updated_at` AS `updated_at`, `users`.`remember_token` AS `remember_token`, `users`.`profile_picture` AS `profile_picture`, `users`.`role` AS `role` FROM `users` WHERE `users`.`role` = 'customer' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
