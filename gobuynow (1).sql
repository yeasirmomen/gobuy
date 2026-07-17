-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2026 年 2 朁E18 日 01:19
-- サーバのバージョン： 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gobuynow`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'RABIN', 'r2437009@icb.ac.jp', '0000'),
(4, 'MOMEN', 'r2437031@icb.ac.jp', 'Y1234');

-- --------------------------------------------------------

--
-- テーブルの構造 `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@gobuynow.com', '$2y$10$JZtD3w10go8Kty0IZ6C.BO.TGqxYbZ2.z9SO3dGv2sX5uFQVHKosG', '2025-11-21 03:19:32');

-- --------------------------------------------------------

--
-- テーブルの構造 `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(4, 9, 5, 1),
(18, 1, 150, 1),
(19, 1, 149, 1),
(20, 1, 148, 1),
(21, 1, 129, 2),
(22, 1, 161, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Electronics', NULL),
(2, 'Clothing', NULL),
(3, 'Accessories', NULL),
(5, 'Kitchen', NULL),
(6, 'Sports', NULL),
(7, 'Baby & Kids', NULL),
(8, 'Smartphones & Accessories', 1),
(9, 'Computers & Laptops', 1),
(10, 'Home Appliances', 1),
(11, 'Cameras & Video', 1),
(12, 'Smartphones', 8),
(13, 'Phone Cases', 8),
(14, 'Screen Protectors', 8),
(15, 'Chargers & Cables', 8),
(16, 'Earphones / Headphones', 8),
(17, 'Laptops', 9),
(18, 'Desktops', 9),
(19, 'Keyboards', 9),
(20, 'Mice', 9),
(21, 'Monitors', 9),
(22, 'External Storage', 9),
(23, 'Digital Cameras', 11),
(24, 'Lenses', 11),
(25, 'Tripods', 11),
(26, 'Action Cameras', 11),
(27, 'Men', 2),
(28, 'Women', 2),
(29, 'Clothes Accessories', 2),
(30, 'T-Shirts', 27),
(31, 'Shirts', 27),
(32, 'Hoodies & Sweatshirts', 27),
(33, 'Jackets & Coats', 27),
(34, 'Pants', 27),
(35, 'Jeans', 27),
(36, 'Shorts', 27),
(37, 'Underwear', 27),
(38, 'Tops & Tees', 28),
(39, 'Dresses', 28),
(40, 'Hoodies & Sweaters', 28),
(41, 'Jackets & Coats', 28),
(42, 'Pants & Jeans', 28),
(43, 'Skirts', 28),
(44, 'Lingerie', 28),
(45, 'Hats & Caps', 29),
(46, 'Belts', 29),
(47, 'Gloves', 29),
(48, 'Scarves', 29),
(49, 'Socks', 29),
(50, 'Bags', 3),
(51, 'Jewelry', 3),
(52, 'Watches', 3),
(53, 'Hats & Headwear', 3),
(54, 'Belts', 3),
(55, 'Sunglasses', 3),
(56, 'Scarves & Gloves', 3),
(57, 'Shoes', 3),
(58, 'Backpacks', 50),
(59, 'Shoulder Bags', 50),
(60, 'Handbags', 50),
(61, 'Tote Bags', 50),
(62, 'Wallets', 50),
(63, 'Necklaces', 51),
(64, 'Earrings', 51),
(65, 'Bracelets', 51),
(66, 'Rings', 51),
(67, 'Digital Watches', 52),
(68, 'Analog Watches', 52),
(69, 'Smartwatches', 52),
(70, 'Caps', 53),
(71, 'Beanies', 53),
(72, 'Sun Hats', 53),
(73, 'UV Protection', 55),
(74, 'Fashion Sunglasses', 55),
(75, 'Scarves', 56),
(76, 'Gloves', 56),
(77, 'Mittens', 56),
(78, 'Men''s Shoes', 57),
(79, 'Women''s Shoes', 57),
(80, 'Sports Shoes', 57),
(81, 'Casual Shoes', 57),
(82, 'Formal Shoes', 57),
(83, 'Sandals & Flip-flops', 57),
(84, 'Boots', 57),
(85, 'Cookware', 5),
(86, 'Kitchen Tools & Utensils', 5),
(87, 'Storage & Organization', 5),
(88, 'Small Kitchen Appliances', 5),
(89, 'Baking Tools', 5),
(90, 'Tableware', 5),
(91, 'Pots', 85),
(92, 'Pans', 85),
(93, 'Woks', 85),
(94, 'Pressure Cookers', 85),
(95, 'Steamers', 85),
(96, 'Casserole Pots', 85),
(97, 'Skillets & Frying Pans', 85),
(98, 'Grill Pans', 85),
(99, 'Non-stick Cookware', 85),
(100, 'Cast Iron Cookware', 85),
(101, 'Stainless Steel Cookware', 85),
(102, 'Cookware Sets', 85),
(103, 'Knives', 86),
(104, 'Cutting Boards', 86),
(105, 'Spatulas', 86),
(106, 'Whisks', 86),
(107, 'Peelers', 86),
(108, 'Tongs & Scissors', 86),
(109, 'Measuring Tools', 86),
(110, 'Colanders & Sieves', 86),
(111, 'Rolling Pins', 86),
(112, 'Food Containers', 87),
(113, 'Airtight Jars & Canisters', 87),
(114, 'Spice Racks', 87),
(115, 'Bento Boxes', 87),
(116, 'Fridge Organizers', 87),
(117, 'Drawer Organizers', 87),
(118, 'Rice Cooker', 88),
(119, 'Electric Kettle', 88),
(120, 'Blender & Mixer', 88),
(121, 'Toaster', 88),
(122, 'Coffee Maker', 88),
(123, 'Air Fryer', 88),
(124, 'Food Processor', 88),
(125, 'Baking Trays', 89),
(126, 'Mixing Bowls', 89),
(127, 'Measuring Cups & Spoons', 89),
(128, 'Cake Molds', 89),
(129, 'Cookie Cutters', 89),
(130, 'Decorating Tools', 89),
(131, 'Plates', 90),
(132, 'Bowls', 90),
(133, 'Cups & Mugs', 90),
(134, 'Glassware', 90),
(135, 'Cutlery', 90),
(136, 'Serving Trays & Bowls', 90),
(137, 'Fitness & Gym', 6),
(138, 'Outdoor Sports', 6),
(139, 'Team Sports', 6),
(140, 'Sportswear', 6),
(141, 'Dumbbells', 137),
(142, 'Yoga Mats', 137),
(143, 'Resistance Bands', 137),
(144, 'Fitness Machines', 137),
(145, 'Camping Gear', 138),
(146, 'Hiking Gear', 138),
(147, 'Tents', 138),
(148, 'Backpacks', 138),
(149, 'Football', 139),
(150, 'Basketball', 139),
(151, 'Volleyball', 139),
(152, 'Badminton', 139),
(153, 'T-Shirts', 140),
(154, 'Shorts', 140),
(155, 'Shoes', 140),
(156, 'Jackets', 140),
(157, 'Baby Essentials', 7),
(158, 'Kids Clothing', 7),
(159, 'Toys', 7),
(160, 'Baby Gear', 7),
(161, 'Diapers', 157),
(162, 'Baby Wipes', 157),
(163, 'Baby Bottles', 157),
(164, 'Pacifiers', 157),
(165, 'Tops', 158),
(166, 'Pants', 158),
(167, 'Dresses', 158),
(168, 'Jackets', 158),
(169, 'Sleepwear', 158),
(170, 'Educational Toys', 159),
(171, 'Soft Toys', 159),
(172, 'Building Blocks', 159),
(173, 'Cars & Figures', 159),
(174, 'Strollers', 160),
(175, 'Car Seats', 160),
(176, 'Baby Carrier', 160),
(177, 'High Chairs', 160);

-- --------------------------------------------------------

--
-- テーブルの構造 `category_tags`
--

CREATE TABLE `category_tags` (
  `category_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `category_tags`
--

INSERT INTO `category_tags` (`category_id`, `tag_id`) VALUES
(1, 186),
(1, 187),
(1, 188),
(1, 189),
(1, 190),
(1, 191),
(1, 192),
(1, 193),
(1, 194),
(1, 195),
(1, 196),
(1, 197),
(1, 198),
(1, 199),
(1, 200),
(1, 201),
(1, 202),
(1, 203),
(1, 204),
(1, 205),
(2, 186),
(2, 187),
(2, 206),
(2, 207),
(2, 208),
(2, 209),
(2, 210),
(2, 211),
(2, 212),
(2, 213),
(2, 214),
(2, 215),
(2, 216),
(2, 217),
(2, 218),
(2, 219),
(2, 220),
(2, 221),
(2, 222),
(3, 186),
(3, 187),
(3, 193),
(3, 200),
(3, 206),
(3, 223),
(3, 224),
(3, 225),
(3, 226),
(3, 227),
(3, 228),
(3, 229),
(3, 230),
(3, 231),
(3, 232),
(3, 233),
(3, 234),
(3, 235),
(3, 236),
(3, 237),
(5, 186),
(5, 187),
(5, 193),
(5, 199),
(5, 221),
(5, 232),
(5, 238),
(5, 239),
(5, 240),
(5, 241),
(5, 242),
(5, 243),
(5, 244),
(5, 245),
(5, 246),
(5, 247),
(5, 248),
(6, 186),
(6, 187),
(6, 193),
(6, 232),
(6, 249),
(6, 250),
(6, 251),
(6, 252),
(6, 253),
(6, 254),
(6, 255),
(6, 256),
(6, 257),
(6, 258),
(6, 259),
(6, 260),
(6, 261),
(6, 262),
(6, 263),
(7, 186),
(7, 187),
(7, 193),
(7, 221),
(7, 228),
(7, 232),
(7, 251),
(7, 264),
(7, 265),
(7, 266),
(7, 267),
(7, 268),
(7, 269),
(7, 270),
(7, 271),
(7, 272),
(7, 273),
(7, 274);

-- --------------------------------------------------------

--
-- テーブルの構造 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `order_date`, `status`) VALUES
(1, 1, '0.00', '2025-11-19 11:20:42', 'Delivered'),
(2, 2, '0.00', '2025-11-19 11:41:38', 'Delivered'),
(3, 1, '6060.00', '2025-11-21 11:13:03', 'Delivered'),
(4, 1, '0.00', '2025-11-21 11:13:12', 'Delivered'),
(5, 1, '2434.00', '2025-11-21 11:20:27', 'Delivered'),
(6, 1, '2767.00', '2025-11-21 11:33:16', 'Delivered'),
(7, 7, '110.00', '2025-11-26 11:56:57', 'Delivered'),
(8, 7, '0.00', '2025-11-26 11:57:07', 'Delivered'),
(9, 7, '0.00', '2025-11-26 11:57:11', 'Delivered'),
(10, 7, '55.00', '2025-11-26 11:57:43', 'Delivered'),
(11, 7, '55.00', '2025-11-26 12:04:18', 'Delivered'),
(12, 7, '55.00', '2025-11-26 12:04:44', 'Delivered'),
(13, 7, '297.00', '2025-11-26 12:05:57', 'Delivered'),
(14, 7, '55.00', '2025-11-26 12:08:56', 'Delivered'),
(15, 7, '55.00', '2025-11-26 12:09:02', 'Delivered'),
(16, 7, '55.00', '2025-11-26 12:11:03', 'Delivered'),
(17, 7, '231.00', '2025-11-26 12:11:21', 'Delivered'),
(18, 7, '55.00', '2025-11-26 12:15:23', 'Delivered'),
(19, 7, '176.00', '2025-11-26 12:15:40', 'Delivered'),
(20, 7, '55.00', '2025-11-26 12:17:58', 'Delivered'),
(21, 7, '0.00', '2025-11-27 09:50:12', 'Delivered'),
(22, 7, '0.00', '2025-11-27 09:52:39', 'Delivered'),
(23, 7, '352.00', '2025-11-27 09:54:59', 'Delivered'),
(24, 7, '352.00', '2025-11-27 09:55:40', 'Delivered'),
(25, 7, '473.00', '2025-11-27 10:18:32', 'Delivered'),
(26, 7, '605.00', '2025-12-01 10:18:26', 'Delivered'),
(27, 7, '121.00', '2025-12-01 12:10:09', 'Delivered'),
(28, 8, '605.00', '2025-12-01 12:17:44', 'Delivered'),
(29, 7, '363.00', '2025-12-02 09:52:07', 'Delivered'),
(30, 7, '121.00', '2025-12-02 10:32:19', 'Delivered'),
(31, 7, '999.00', '2025-12-02 11:21:39', 'Delivered'),
(32, 7, '121.00', '2025-12-03 10:40:13', 'Delivered'),
(34, 7, '7960.00', '2025-12-08 09:45:04', 'Delivered'),
(35, 7, '263780.00', '2025-12-16 09:52:12', 'Pending'),
(36, 7, '701500.00', '2025-12-16 11:47:55', 'Pending'),
(37, 7, '1663780.00', '2025-12-17 11:17:00', 'Pending'),
(38, 7, '964780.00', '2025-12-17 11:17:20', 'Pending'),
(39, 7, '700000.00', '2025-12-19 08:51:06', 'Pending'),
(40, 7, '3418900.00', '2025-12-19 10:11:42', 'Pending'),
(42, 7, '1555.00', '2025-12-22 11:51:47', 'Delivered'),
(43, 1, '4552.00', '2026-01-13 09:08:47', 'Delivered'),
(44, 7, '27855.00', '2026-01-19 11:39:09', 'Processing'),
(45, 1, '433660.00', '2026-01-20 09:58:54', 'Delivered'),
(46, 7, '5270.00', '2026-01-21 10:04:01', 'Pending'),
(47, 7, '5690.00', '2026-01-21 10:05:49', 'Pending'),
(48, 7, '1800.00', '2026-01-21 10:10:21', 'Pending'),
(49, 7, '5690.00', '2026-01-21 10:15:49', 'Pending'),
(50, 7, '5690.00', '2026-01-21 10:16:07', 'Pending'),
(51, 7, '38770.00', '2026-01-21 10:40:01', 'Pending'),
(52, 3, '1460.00', '2026-01-23 10:51:53', 'Processing'),
(53, 3, '139400.00', '2026-02-09 12:35:15', 'Delivered');

-- --------------------------------------------------------

--
-- テーブルの構造 `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 3, 1, 1, '1212.00'),
(2, 3, 1, 1, '1212.00'),
(3, 3, 1, 1, '1212.00'),
(4, 3, 1, 1, '1212.00'),
(5, 3, 1, 1, '1212.00'),
(6, 5, 2, 1, '1222.00'),
(7, 5, 1, 1, '1212.00'),
(8, 6, 3, 1, '333.00'),
(9, 6, 2, 1, '1222.00'),
(10, 6, 1, 1, '1212.00'),
(11, 7, 2, 1, '55.00'),
(12, 7, 2, 1, '55.00'),
(13, 10, 2, 1, '55.00'),
(14, 11, 2, 1, '55.00'),
(15, 12, 2, 1, '55.00'),
(16, 13, 1, 1, '121.00'),
(17, 13, 2, 1, '55.00'),
(18, 13, 1, 1, '121.00'),
(19, 14, 2, 1, '55.00'),
(20, 15, 2, 1, '55.00'),
(21, 16, 2, 1, '55.00'),
(22, 17, 2, 1, '55.00'),
(23, 17, 1, 1, '121.00'),
(24, 17, 2, 1, '55.00'),
(25, 18, 2, 1, '55.00'),
(26, 19, 1, 1, '121.00'),
(27, 19, 2, 1, '55.00'),
(28, 20, 2, 1, '55.00'),
(29, 23, 2, 2, '55.00'),
(30, 23, 1, 2, '121.00'),
(31, 24, 1, 2, '121.00'),
(32, 24, 2, 2, '55.00'),
(33, 25, 1, 3, '121.00'),
(34, 25, 2, 2, '55.00'),
(35, 26, 1, 5, '121.00'),
(36, 27, 1, 1, '121.00'),
(37, 28, 1, 5, '121.00'),
(38, 29, 1, 3, '121.00'),
(39, 30, 1, 1, '121.00'),
(40, 31, 7, 1, '999.00'),
(41, 32, 1, 1, '121.00'),
(43, 34, 6, 2, '3980.00'),
(44, 35, 11, 1, '263780.00'),
(45, 36, 8, 2, '500.00'),
(46, 36, 10, 1, '700000.00'),
(47, 36, 9, 1, '500.00'),
(48, 37, 11, 1, '263780.00'),
(49, 37, 10, 2, '700000.00'),
(50, 38, 11, 1, '263780.00'),
(51, 38, 10, 1, '700000.00'),
(52, 38, 9, 1, '500.00'),
(53, 38, 8, 1, '500.00'),
(54, 39, 10, 1, '700000.00'),
(55, 40, 10, 3, '700000.00'),
(56, 40, 11, 5, '263780.00'),
(60, 42, 17, 1, '1555.00'),
(61, 43, 7, 3, '999.00'),
(62, 43, 17, 1, '1555.00'),
(63, 44, 22, 2, '11800.00'),
(64, 44, 20, 1, '4200.00'),
(65, 44, 2, 1, '55.00'),
(66, 45, 22, 1, '11800.00'),
(67, 45, 20, 1, '4200.00'),
(68, 45, 42, 1, '144800.00'),
(69, 45, 41, 1, '263780.00'),
(70, 45, 39, 1, '3980.00'),
(71, 45, 44, 1, '2800.00'),
(72, 45, 92, 1, '2300.00'),
(73, 46, 150, 1, '5270.00'),
(74, 47, 151, 1, '5690.00'),
(75, 48, 146, 1, '1800.00'),
(76, 49, 151, 1, '5690.00'),
(77, 50, 151, 1, '5690.00'),
(78, 51, 150, 3, '5270.00'),
(79, 51, 151, 4, '5690.00'),
(80, 51, 149, 1, '200.00'),
(81, 52, 122, 1, '580.00'),
(82, 52, 124, 1, '880.00'),
(83, 53, 162, 1, '1800.00'),
(84, 53, 161, 3, '2000.00'),
(85, 53, 157, 1, '1800.00'),
(86, 53, 52, 1, '129800.00');

-- --------------------------------------------------------

--
-- テーブルの構造 `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stock` int(11) NOT NULL DEFAULT '0',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `image`, `description`, `created_at`, `stock`, `is_hidden`) VALUES
(1, 'round neck', 121, NULL, 'vneck2.jpg', 'yfctyfctc', '2025-11-26 01:14:34', 484, 1),
(2, 'v neck tshirt', 55, 3, 'vneck.jpg', 'v neck tshirt ', '2025-11-26 02:51:48', 2554, 1),
(3, 'knife', 555, NULL, 'product02.jpg', 'stainless steel knife ', '2025-12-01 00:16:54', 2, 0),
(5, 'T-shirt', 0.1, NULL, '2005-2.jpg', '', '2025-12-01 03:29:39', 10, 0),
(6, 'スリムフィット チノパン', 3980, NULL, '7154.jpg', '', '2025-12-01 03:39:59', 48, 0),
(10, 'Dell 14 ノートパソコン', 700000, 17, NULL, '仕様詳細\r\nプロセッサー\r\nインテル® Core™ 5 120U (10 コア, 最大 5.0 GHzまで可能)\r\n\r\nオペレーティングシステム\r\n(Dell Technologiesはビジネスに Windows 11 Pro をお勧めします)\r\nWindows 11 Pro\r\n\r\nグラフィックス\r\nインテル® グラフィックス\r\n\r\nディスプレイ\r\n14.0インチ 2K (1920x1200) 非光沢 非タッチ 300nits WVA/IPS ディスプレイ\r\n\r\nメモリー \r\n16 GB: 1 x 16 GB, DDR5, 5200 MT/s\r\n\r\nストレージ(M.2 NVMe SSD)\r\n512 GB, M.2, PCIe NVMe, SSD\r\n\r\n本体カラー\r\nカーボン ブラック(プラスチック)\r\n\r\nMicrosoft ソフトウェア \r\nMicrosoft 365 30日試用版（デジタルライセンス版）\r\n\r\nホーム セキュリティおよびスモール ビジネス セキュリティ ソリューション\r\nMcAfee Business Protection 1年版\r\n\r\n保守サービス（延長・アップグレード）\r\nベーシック オンサイト サービス - ハードウェア サポートのみによるリモート診断後-JP (Pro OS), 12 ヶ月\r\n\r\nアクシデンタル ダメージ サービス & アクシデンタル ダメージ サービス(盗難対応オプション付き)\r\nアクシデンタル ダメージ サービス, 12 ヶ月\r\n\r\nキーボード\r\nCopilotキー搭載 カーボン ブラック 日本語 非バックライト キーボード\r\n\r\nポート\r\n2 x USB 3.2 Gen 1 Type-A (5 Gbps)ポート\r\n1 x USB 3.2 Gen 2 (10 Gbps) Type-C®（Power DeliveryおよびDisplayPort™対応）\r\n1 x ヘッドセット（ヘッドホンとマイクのコンボ）ポート\r\n1 x HDMI 1.4ポート\r\n1 x 電源アダプター ポート\r\n\r\n注：HDMIでサポートされる最大解像度は1920x1080 @60Hzです。4K/2K出力には対応していません。 \r\n\r\nスロット\r\n1 x SDカード スロット\r\n1 x くさび型ロック スロット\r\n\r\n寸法と重量\r\nアルミニウム：\r\n高さ（前面）：15.86 mm\r\n高さ（最高部）：18.90 mm\r\n高さ（背面）：17.31 mm\r\n幅：314.00 mm\r\n奥行き：226.15 mm\r\n重量（最小）：1.56 kg\r\n重量（最大）：1.65 kg\r\n\r\nプラスチック：\r\n高さ（前面）：16.90 mm\r\n高さ（最高部）：19.90 mm\r\n高さ（背面）：18.07 mm\r\n幅：314.00 mm\r\n奥行き：226.15 mm\r\n重量（最小）：1.54 kg\r\n重量（最大）：1.69 kg\r\n\r\nタッチパッド\r\nPrecisionタッチパッド\r\n\r\nカメラ\r\n720p (30 fps)、HD RGBカメラ、シングル マイク\r\n1080p (30 fps)、ワイドスクリーン フルHD RGBカメラ、デュアルアレイ マイク\r\n\r\nマルチメディア\r\nステレオ スピーカー（Waves MaxxAudio® ProおよびDolby Atmos Core対応）、2 W x 2 = 合計4 W\r\n\r\nシャーシ\r\n外装シャーシ素材：\r\nアルミニウム製外装シェル\r\nプラスチック製外装シェル\r\n\r\nネットワーク アダプター \r\nRealtek Wi-Fi 6 RTL8852BE, 2x2, 802.11ax, MU-MIMO, Bluetooth® ワイヤレス カード\r\n\r\nプライマリー バッテリー \r\n4 セル バッテリー, 54WHr (内蔵)\r\n\r\n電源 \r\n65W ACアダプター\r\n\r\nパームレスト\r\nカーボン ブラック 電源 ボタン 指紋認証リーダーなし', '2025-12-12 01:46:32', 7, 0),
(11, 'ノートパソコン LAVIE NEXTREME(X1475/JAS) ルナグレー PC-X1475JAS', 263780, 17, NULL, '14', '2025-12-15 04:17:39', 32, 0),
(17, 'sunglass', 1555, 55, NULL, 'this is awsome', '2025-12-19 05:10:56', 998, 0),
(18, 'Urban Travel Backpack', 6800, 50, NULL, 'This versatile backpack is designed for students, travelers, and commuters who need both comfort and durability.\r\nIt features a padded 15.6-inch laptop compartment, anti-sweat back cushioning, and reinforced stitching on all major stress points.\r\nWater-resistant fabric keeps your belongings protected during light rain or outdoor activities.\r\nMultiple pockets—including a hidden security pocket—allow easy organization of cables, chargers, notebooks, and personal items.\r\nThe ergonomic straps reduce shoulder pressure, making it ideal for long daily use.', '2026-01-19 02:17:22', 100, 0),
(20, 'Casual Shoulder Bag', 4200, 50, NULL, 'A lightweight PU-leather shoulder bag suitable for everyday outings and casual fashion.\r\nThe soft body structure allows it to expand slightly to hold more items without losing shape.\r\nInside, it includes a zip-secured pocket for valuables and an open pocket for phones or cards.\r\nIts smooth matte finish resists scratches, making it durable for long-term use.\r\nPerfect for students, office workers, or anyone wanting a simple yet stylish accessory.', '2026-01-19 02:21:07', 98, 0),
(22, 'Premium Leather Handbag', 11800, 50, NULL, 'Made from high-quality genuine leather, this handbag offers an elegant and luxurious look.\r\nThe structured body maintains its shape even when fully packed, ensuring a polished appearance.\r\nA detachable crossbody strap allows for multiple carrying styles depending on the occasion.\r\nThe interior includes two compartments and a zip divider, helping users keep items neatly arranged.\r\nSuitable for business meetings, formal events, or upscale daily use.', '2026-01-19 02:23:26', 17, 0),
(27, 'Slim RFID-Protected Wallet', 2500, 50, NULL, 'A compact wallet designed to fit comfortably into pockets without bulk.\r\nThe RFID-blocking layer protects all cards from unauthorized scanning or data theft.\r\nThe wallet includes multiple card slots, a clear ID window, and a side pocket for bills.\r\nHigh-quality vegan leather provides a smooth texture while resisting wear and creasing.\r\nGreat for travelers or minimalists who prefer security and simplicity.', '2026-01-19 02:35:02', 1000, 0),
(32, 'T-Shirt SportFit', 3750, 2, NULL, 'Material: Polyester Dry-Fit, quick-drying and breathable\r\nStyle: Designed for workouts, gym, and running\r\nSeason: All seasons, especially summer\r\nRecommended age: 14–35', '2026-01-19 02:54:02', 50, 0),
(37, 'T-Shirt Premium Cotton', 3200, 2, NULL, 'Material: 100% organic cotton, soft and non-irritating\r\nStyle: Casual – suitable for school, work, or daily wear\r\nSeason: Spring / Autumn\r\nRecommended age: 18–45', '2026-01-19 02:56:58', 50, 0),
(38, 'intel Core i5/メモリ：16GB/SSD:512GB 134,800円', 134800, 1, NULL, 'インテル プロセッサーを搭載し、日常的なコンピューティング向けに設計されたDell 15で、To Doリストをすばやく片付けましょう。カラーは、プラチナ シルバーとテクスチャー仕上げのカーボンブラックからお選びいただけます。最新のMicrosoft Office 2024搭載モデル。', '2026-01-19 02:59:18', 50, 0),
(39, 'T-Shirt Streetwear Oversize', 3980, 2, NULL, 'Material: 260gsm heavy cotton, excellent shape retention\r\nStyle: Streetwear – urban fashion style\r\nSeason: Spring – Summer – Autumn\r\nRecommended age: 16–30', '2026-01-19 02:59:23', 49, 0),
(40, 'T-Shirt EcoBamboo', 2260, 2, NULL, 'Material: Bamboo fiber, ultra-soft and antibacterial\r\nStyle: Minimal – comfortable and lightweight\r\nSeason: Summer & Autumn\r\nRecommended age: 20–50', '2026-01-19 03:03:30', 50, 0),
(41, 'ノートパソコン LAVIE NEXTREME(X1475/JAS) ルナグレー PC-X1475JAS', 263780, 1, NULL, '[14.0型 /Windows11 Home /AMD Ryzen AI 7 /メモリ：32GB /SSD：512GB /Office Home and Business /2024年秋冬モデル]', '2026-01-19 03:03:56', 7, 0),
(42, 'Windows 11 Pro、Core i5、16GBメモリ、約512GB SSD、Office あり', 144800, 1, NULL, 'FMV Lite WA1/J2\r\n2024年11月モデル Core i5搭載\r\n\r\nWEB専用モデル', '2026-01-19 03:05:56', 41, 0),
(43, 'Classic Oxford Shirt', 3880, 2, NULL, 'Material: Oxford cotton weave, sturdy yet comfortable\r\nStyle: Smart casual, ideal for office or semi-formal events\r\nSeason: All seasons; pairs well with jackets in winter\r\nRecommended age: 20–45', '2026-01-19 03:07:09', 50, 0),
(44, 'Genuine Leather Belt', 2800, 54, NULL, 'Crafted from real leather, this belt is both durable and stylish.\r\nThe polished buckle adds a sophisticated touch to both casual and formal outfits.\r\nLeather softens over time, becoming more comfortable with use.\r\nPerfect for business attire or smart-casual fashion.', '2026-01-19 03:08:09', 99, 1),
(45, 'ノートパソコン FMV Note Uシリーズ U500-K3 ピクトブラック FMVU500K3B [14.0型 /Windows11 Home /intel Core i5 /メモリ：16GB /SSD：512GB /Office Home and Business /2025年10月モデル]', 186780, 1, NULL, 'タッチパネル非対応', '2026-01-19 03:11:23', 50, 0),
(46, 'Canvas Fabric Belt', 1300, 54, NULL, 'A lightweight, breathable belt that matches jeans, shorts, or outdoor wear.\r\nThe adjustable metal buckle ensures a secure fit for any waist size.\r\nMade from durable woven fabric resistant to fraying.\r\nIdeal for travel, hiking, or everyday use.', '2026-01-19 03:11:27', 1000, 0),
(48, 'Street Style Cap', 1500, 70, NULL, 'A breathable cotton cap with an adjustable strap for a perfect fit.\r\nIts curved brim helps reduce sunlight exposure while maintaining a stylish look.\r\nThe fabric is lightweight and suitable for all-day wear.\r\nIdeal for casual outfits, outdoor walks, and sports activities.', '2026-01-19 03:14:01', 1000, 0),
(49, 'ノートパソコン LAVIE SOL(S1355/LAS) プラチナシルバー PC-S1355LAS [13.3型 /Windows11 Home /intel Core i5 /メモリ：16GB /SSD：512GB /Office Home and Business /2025年秋冬モデル]', 197780, 1, NULL, 'ntel corei5/メモリ：16GB', '2026-01-19 03:18:16', 5, 0),
(50, 'Winter Knit Beanie', 1200, 70, NULL, 'ade from soft acrylic yarn, this beanie provides excellent insulation in cold weather.\r\nIts stretchy knit design fits most head sizes comfortably.\r\nThe inner lining keeps warmth inside while preventing irritation.\r\nPerfect for winter travel, skiing, or daily use during cold seasons.', '2026-01-19 03:19:11', 0, 0),
(51, 'Wide-Brim Sun Hat', 2000, 70, NULL, 'This hat offers superior UV protection for beach trips and outdoor adventures.\r\nIts wide brim shields the face and neck from harsh sunlight.\r\nLightweight and foldable, making it easy to carry while traveling.\r\nDesigned with breathable materials to prevent overheating.', '2026-01-19 03:21:59', 100, 1),
(52, 'ノートパソコン Vivobook 15 クワイエットブルー X1504VA-I5165W [15.6型 /Windows11 Home /intel Core i5 /メモリ：16GB /SSD：512GB /WPS Office /2024年7月モデル] 【在庫限り】', 129800, 1, NULL, '', '2026-01-19 03:22:25', 31, 0),
(53, 'ノートパソコン レッツノート QRシリーズ カームグレイ CF-QR4JDTCR [12.4型 /Windows11 Pro /intel Core i5 /メモリ：16GB /SSD：512GB /2025年6月モデル]', 339130, 1, NULL, 'カームグレイ/intel core i5/なし', '2026-01-19 03:33:52', 20, 0),
(54, 'Windows 11 Pro、Core i5、16GBメモリ、約512GB SSD、Office あり', 144800, 1, NULL, 'FMV Lite WA1/J2\r\n2024年11月モデル Core i5搭載\r\n\r\nWEB専用モデル', '2026-01-19 03:35:55', 6, 0),
(55, 'ノートパソコン IdeaPad Slim 3i Gen10 ルナグレー 83K20018JP [16.0型 /Windows11 Home /intel Core i5 /メモリ：16GB /SSD：512GB /Office Home and Business /2025年2月モデル]', 135320, 1, NULL, 'i7', '2026-01-19 03:37:08', 53, 0),
(56, 'iphone17', 116820, 1, NULL, '256g', '2026-01-19 03:41:22', 20, 0),
(58, 'iPhone17 Pro Max 2TB MFYJ4J/A シルバー docomo', 256000, 1, NULL, '', '2026-01-19 03:44:09', 20, 0),
(59, 'Linen Breeze Shirt', 3450, 2, NULL, 'Material: 100% breathable linen\r\nStyle: Relaxed casual, perfect for vacations or summer outings\r\nSeason: Best for spring and summer\r\nRecommended age: 18–40', '2026-01-19 03:46:48', 50, 0),
(60, '【SIMフリー】Galaxy Z Fold7 1TB Blue Shadow', 19200, 1, NULL, '', '2026-01-19 03:48:08', 30, 0),
(61, 'Stretch Formal Shirt', 4250, 2, NULL, 'Material: Cotton–spandex blend for flexibility and wrinkle resistance\r\nStyle: Formal or business attire, suitable for suits\r\nSeason: All seasons\r\nRecommended age: 25–50', '2026-01-19 03:50:49', 50, 0),
(62, '【SIMフリー】Galaxy Z Fold7 1TB Jetblack', 19200, 1, NULL, 'aaa', '2026-01-19 03:52:35', 40, 0),
(63, 'プラットフォーム Android™ 14    プロセッサー MediaTek Dimensity 7300X (2.5GHz x 4+ 2.0GHz x 4)', 250000, 1, NULL, 'インメモリ ※2\r\n12GB\r\n\r\n\r\n\r\n内部ストレージ ※3\r\n512GB\r\n\r\n\r\n\r\n外部メディア規格\r\n-\r\n\r\n\r\n\r\n外部メディア最大容量\r\n-\r\n\r\n\r\n\r\nネットワーク\r\n2G：-\r\n\r\n3G：W-CDMA B1/B2/B4/B5/B8\r\n\r\n4G：LTE B1/B2/B3/B4/B5/B7/B8/B11/B12/B17/B18/B19/\r\n\r\n　　 B20/B26/B28/B38/B39/B40/B41/B42/B66\r\n\r\n5G：n1/n3/n5/n28/n41/n66/n77/n78\r\n\r\n\r\n\r\n通信機能\r\nWi-Fi：Wi-Fi 802.11 a/b/g/n/ac/ax\r\n(2.4GHz / 5GHz / 6GHz) Wi-Fi 6E\r\n\r\nBluetooth®：Bluetooth® 5.4\r\n\r\nテザリング：〇\r\n\r\n\r\n\r\nSIM\r\nタイプ：nanoSIM/eSIM\r\n\r\nスロット数：1 (nanoSIM)、DSDV※4\r\n\r\n\r\n\r\n位置情報サービス\r\nGPS、A-GPS、GLONASS、Galileo、BeiDou、QZSS\r\n\r\n\r\nカメラ\r\nアウトカメラ\r\n\r\n　約5,000万画素メイン OIS (f/1.7)\r\n\r\n　Instant-all Pixel Focus\r\n\r\n　約1,300万画素 超広角+マクロ (f/2.2、120°)\r\n\r\n　LEDフラッシュ\r\n\r\nインカメラ\r\n\r\n　約3,200万画素 (f/2.4)\r\n\r\n\r\n\r\n動画録画\r\nアウトカメラ：\r\n\r\n　メイン：4K UHD(30fps) , FHD (60/30fps)\r\n\r\n　超広角：4K UHD (30fps) , FHD (60/30fps)\r\n\r\n　マクロ：4K UHD (30fps) , FHD (60/30fps)\r\n\r\nインカメラ：\r\n\r\n　4K UHD (30fps) , FHD (60/30fps)\r\n\r\n\r\n\r\nインタフェース\r\nUSB Type-C (USB 2.0)\r\n\r\n\r\n\r\nセンサー\r\n加速度計、近接センサー、環境照度センサー、\r\nジャイロセンサー、eコンパス、ホールセンサー\r\n\r\n\r\n\r\n生体認証\r\n指紋認証、顔認証\r\n\r\n\r\n\r\nNFC/おサイフケータイⓇ ※5\r\n〇/〇\r\n\r\n\r\n\r\nFMラジオ\r\n-\r\n\r\n\r\n\r\nジェスチャー（Motoアクション）\r\nクイック起動：〇\r\n\r\nサイドバー：〇\r\n\r\nクイック撮影：〇\r\n\r\n簡易ライト：〇\r\n\r\n3本指でのスクリーンショット：〇\r\n\r\n持ち上げてロック解除：〇\r\n\r\n下向きでマナーモード：〇\r\n\r\n手を伸ばして消音：〇\r\n持ち上げて消音：〇\r\n\r\nスワイプで分割：〇\r\n\r\n\r\n\r\nディスプレイ\r\nピークディスプレイ：-\r\n\r\nロック画面：〇\r\n\r\n親切ディスプレイ：〇\r\n\r\nエッジライト：-\r\n\r\n\r\n\r\nプレイ\r\nゲーム：〇\r\n\r\nメディアコントロール：〇\r\n\r\nDolby Atmos：〇\r\n\r\n\r\n\r\nMoto Secure\r\n〇\r\n\r\n\r\n\r\nスピーカー\r\nステレオスピーカー\r\n\r\n\r\n\r\nバッテリー ※6\r\n4,200mAh\r\n\r\n\r\n\r\n充電方式\r\n30W TurboPower™ チャージ対応、15W ワイヤレス充電(Qi対応)\r\n\r\n\r\n\r\nサイズ(高さx幅x厚さ)\r\n約171.30mm x 73.99mm x 7.25mm（最薄部） \r\n\r\n(折りたたみ時 : 約88.08mm x 73.99mm x 15.85mm)\r\n\r\n\r\n\r\n重さ\r\n約188g\r\n\r\n\r\n\r\n防水防塵\r\nIPX8', '2026-01-19 03:55:05', 23, 0),
(64, 'Xiaomi POCO C71 スマートフォン グローバル版 3GB+64GB UNISOC T7250プロセッサー 6.88インチドットドロップディスプレイ 最大120Hzリフレッシュレート 32MP メインカメラ 5200mAh(typ)バッテリー 15W急速充電 指紋認証 顔認証', 100000, 1, NULL, '3GB+64GB', '2026-01-19 03:56:49', 23, 0),
(65, 'Flannel Comfort Shirt', 3880, 2, NULL, 'Material: Soft brushed flannel cotton\r\nStyle: Casual, outdoor-friendly, great for layering\r\nSeason: Autumn and winter\r\nRecommended age: 18–45', '2026-01-19 03:57:16', 30, 0),
(66, 'Heart Pendant Necklace', 3200, 51, NULL, 'This delicate heart pendant necklace symbolizes love and elegance, suitable for everyday wear or gifting.\r\nCrafted from stainless steel, it maintains shine over time and resists tarnish.\r\nThe adjustable chain allows users to wear it short or long depending on style preference.\r\nThe pendant features a finely polished surface that reflects light beautifully.\r\nPerfect for anniversaries, birthdays, or romantic occasions.', '2026-01-20 00:01:33', 1000, 0),
(67, 'Crystal Hoop Earrings', 1900, 51, NULL, 'Lightweight and comfortable, these hoop earrings are ideal for long hours of wear.\r\nEach hoop is embedded with small, sparkling crystals that add a subtle shine.\r\nHypoallergenic metal makes them safe for sensitive skin.\r\nTheir minimalist style pairs well with both casual outfits and formal dresses.\r\nA timeless accessory suitable for any season.', '2026-01-20 00:02:32', 100, 0),
(68, 'Charm Bracelet', 2800, 51, NULL, 'A stylish bracelet featuring small charms that add personality to the design.\r\nThe chain is adjustable, making it suitable for wrists of various sizes.\r\nEach charm is polished with care to enhance shine and durability.\r\nLightweight materials ensure comfortable daily wear without irritation.\r\nA great gift for those who enjoy collecting symbolic or meaningful charms.', '2026-01-20 00:03:46', 200, 0),
(69, 'Adjustable Ring Set', 1500, 51, NULL, 'This set includes multiple rings with adjustable openings to fit any finger comfortably.\r\nThe metal is nickel-free, making it safe for people with allergies.\r\nEach ring features a unique design, allowing users to stack them creatively.\r\nPolished surfaces maintain their shine even with frequent use.\r\nPerfect for fashion lovers who enjoy customizing their look.', '2026-01-20 00:04:44', 300, 0),
(70, 'Digital Sports Watch', 4000, 52, NULL, 'A rugged sports watch built for outdoor activities and training routines.\r\nWater-resistant up to 50m, making it suitable for swimming and rainy conditions.\r\nFeatures include stopwatch, countdown timer, alarm, and LED backlight.\r\nThe shock-resistant case ensures durability during physical activities.\r\nIdeal for athletes, hikers, and active users.', '2026-01-20 00:06:22', 300, 0),
(71, 'Classic Analog Watch', 7500, 52, NULL, 'A timeless analog watch featuring a stainless-steel case and clean dial.\r\nThe genuine leather strap provides comfort and a traditional aesthetic.\r\nQuartz movement ensures precise timekeeping with minimal maintenance.\r\nSuitable for formal occasions, business attire, or elegant daily wear.\r\nA sophisticated accessory perfect as a gift.', '2026-01-20 00:07:22', 200, 0),
(72, 'POCO C61 グローバルバージョン スマートフォン', 160000, 1, NULL, '滑らかな6.71インチ 90Hzディスプレイ 洗練されたスタイリッシュなデザイン 800万画素AIデュアルカメラシステム 大容量5000mAh(typ)バッテリー 指紋認証とフェイスロック MediaTek Helio G36 プロセッサ 12nmプロセス オクタコア最大2.2GHz メモリ拡張対応 10W充電対応 Bluetooth 5.4 Android 14 デュアル4G対応', '2026-01-20 00:08:10', 8, 0),
(73, 'Smartwatch Lite Edition', 9500, 52, NULL, 'A lightweight smartwatch offering fitness tracking and smart notifications.\r\nIt monitors heart rate, sleep, step count, and calories burned.\r\nCompatible with both iOS and Android devices via Bluetooth.\r\nBattery life lasts up to 5–7 days on a single charge.\r\nGreat for users who want smart features without high prices.', '2026-01-20 00:08:20', 100, 0),
(74, 'Samsung Galaxy A16 4G スマートフォン', 168000, 1, NULL, 'グローバル版 6.7インチ AMOLED 90Hz ディスプレイ 50MP リアカメラ 13MP フロントカメラ 指紋認証 5000mAh バッテリー *充電器なし', '2026-01-20 00:10:19', 32, 0),
(75, 'Lightweight Cotton Scarf', 1600, 56, NULL, 'This breathable cotton scarf is ideal for spring, autumn, or mild weather.\r\nSoft and gentle on the skin, making it comfortable for long wear.\r\nThe simple design pairs easily with casual and semi-formal outfits.\r\nAn excellent everyday accessory for all ages.', '2026-01-20 00:10:53', 500, 0),
(76, 'Winter Thermal Gloves', 1900, 56, NULL, 'These gloves feature touchscreen-compatible fingertips for smartphone use.\r\nThe inner fleece lining provides strong insulation during winter.\r\nWindproof fabric keeps hands warm in harsh outdoor conditions.\r\nSuitable for cycling, commuting, and winter sports.', '2026-01-20 00:11:49', 1000, 0),
(77, 'Xiaomi Xiaomi Redmi 13x 8GB+256GB スマートフォン', 135000, 1, NULL, 'Helio G91-Ultraプロセッサー、6.79インチFHD+ド', '2026-01-20 00:12:23', 20, 0),
(78, 'Knitted Mittens', 1200, 56, NULL, 'Warm and comfortable mittens designed for both children and adults.\r\nThe thick knitted texture traps heat effectively during cold weather.\r\nElastic cuffs ensure a secure fit and prevent cold air from entering.\r\nAvailable in cute and colorful styles.', '2026-01-20 00:13:50', 1000, 0),
(79, 'Google Pixel 7 Pro 128GB SIMフリー', 200000, 1, NULL, 'zz', '2026-01-20 00:14:22', 30, 0),
(80, 'Faux Wool Scarf Set', 2400, 56, NULL, 'A matching scarf and hand warmer set made from soft faux wool.\r\nPerfect as a winter gift thanks to its cozy and stylish appearance.\r\nThe scarf provides warmth without feeling too heavy.\r\nIdeal for holiday seasons and cold-weather travel.', '2026-01-20 00:15:23', 1000, 0),
(81, 'アイリスオーヤマ 単機能レンジ　', 13780, 1, NULL, '１７Ｌ　ターンテーブルタイプ　ホワイト　ＩＭＢ－Ｔ１７８－Ｗ　電子レンジ・オーブンレンジ　１台 【7046-1329】', '2026-01-20 00:17:16', 40, 0),
(82, 'アイリスオーヤマ 単機能レンジ　', 18170, 1, NULL, '１８Ｌ　フラットタイプ　ホワイト　ＩＭＢ－Ｆ１８６－Ｗ　電子レンジ・オーブンレンジ　１台 【7046-1336】', '2026-01-20 00:19:33', 20, 0),
(83, 'UV-400 Protective Sunglasses', 2200, 55, NULL, 'These sunglasses block 99% of UVA and UVB rays using UV-400 lenses.\r\nThe lightweight frame ensures comfortable all-day wear.\r\nDesigned for outdoor sports, driving, and summer travel.\r\nStylish yet functional for daily protection.', '2026-01-20 00:21:22', 500, 0),
(84, 'ツインバード フラット電子レンジ', 18900, 1, NULL, '４６０×３５０×２７５ｍｍ　１台　ＤＲ－ＬＤ２０Ｗ 【7062-8173】', '2026-01-20 00:22:06', 23, 0),
(85, 'Oversized Fashion Sunglasses', 2700, 55, NULL, 'Large lenses and bold frames give these sunglasses a trendy and dramatic appearance.\r\nPerfect for fashion lovers who enjoy standout accessories.\r\nThe tinted lenses reduce glare and enhance visual comfort.\r\nGreat for photo shoots or street-style outfits.', '2026-01-20 00:22:17', 600, 0),
(86, '高周波出力５００Ｗ／６５０Ｗ（５０／６０Ｈｚ）のヘルツフリー。', 17380, 1, NULL, '庫内が広くお手入れ簡単なフラットタイプ。あたため・お弁当・飲み物・お酒の４つのオートメニュー。消音機能も', '2026-01-20 00:23:18', 30, 0),
(87, '-東芝ライフスタイル 単機能レンジ１７Ｌ', 30800, 1, NULL, 'フラットタイプ　ＥＲ－ＷＳ１７（Ｗ）　電子レンジ・オーブンレンジ　１台', '2026-01-20 00:24:47', 21, 0),
(88, 'Classic Fleece Hoodie', 4500, 2, NULL, 'Material: Soft cotton–poly fleece blend, warm and durable\r\nStyle: Casual everyday wear, ideal for outdoor activities\r\nSeason: Autumn and winter\r\nRecommended age: 16–40', '2026-01-20 00:28:34', 50, 0),
(89, 'Oversized Street Hoodie', 5480, 2, NULL, 'Material: Heavyweight 320gsm cotton with thick inner lining\r\nStyle: Streetwear oversized fit, trendy and fashionable\r\nSeason: All seasons, especially cooler evenings\r\nRecommended age: 15–35', '2026-01-20 00:30:20', 50, 0),
(90, 'Lightweight Zip Sweatshirt', 6750, 2, NULL, 'Material: Breathable lightweight cotton jersey\r\nStyle: Minimalist zip-up design, suitable for layering\r\nSeason: Spring and autumn\r\nRecommended age: 18–45', '2026-01-20 00:31:20', 40, 0),
(91, 'Tech Performance Sweatshirt', 5280, 2, NULL, 'Material: Quick-dry polyester blend with moisture-wicking technology\r\nStyle: Sporty activewear, designed for workouts and running\r\nSeason: All seasons\r\nRecommended age: 18–50', '2026-01-20 00:32:14', 30, 0),
(92, 'Ultra-Soft Diapers', 2300, 157, NULL, 'These premium newborn diapers are designed with a super-gentle surface to protect sensitive skin.\r\nThey offer highly absorbent padding, preventing overnight leakage and keeping the baby dry.\r\nThe breathable outer layer reduces discomfort and heat buildup.\r\nA wetness indicator turns color when changing is needed.\r\nIdeal for newborns requiring full comfort and skin safety.', '2026-01-20 00:32:43', 499, 0),
(93, 'Classic Denim Jacket', 3880, 2, NULL, 'Material: 100% heavy denim cotton\r\nStyle: Casual street style, versatile for daily wear\r\nSeason: Spring, autumn, and mild winter\r\nRecommended age: 16–40', '2026-01-20 00:33:05', 30, 0),
(94, 'Sensitive Skin Baby Wipes', 980, 157, NULL, 'Made with aloe extract and purified water, these wipes soothe the baby’s skin without irritation.\r\nAlcohol-free and fragrance-free, making them safe for daily use.\r\nExtra-thick fiber ensures gentle cleaning without tearing.\r\nResealable packaging keeps wipes fresh and moist over time.\r\nPerfect for diaper changes, feeding messes, and quick cleanups.', '2026-01-20 00:33:25', 1000, 0),
(97, 'Thermal Puffer Coat', 7880, 2, NULL, 'Material: Water-resistant polyester shell with warm synthetic insulation\r\nStyle: Winter outerwear, ideal for cold climates\r\nSeason: Winter\r\nRecommended age: 18–50', '2026-01-20 00:34:51', 20, 0),
(98, 'Anti-Colic Baby Bottle', 1400, 157, NULL, 'This bottle includes an anti-colic ventilation system to reduce gas and stomach discomfort.\r\nThe soft silicone nipple mimics natural breastfeeding flow.\r\nDesigned for easy gripping with one hand.\r\nWide neck opening simplifies cleaning and sterilizing.\r\nPerfect for smooth feeding transitions from breast to bottle.', '2026-01-20 00:35:06', 5000, 0),
(99, 'Orthodontic Pacifier (Twin Pack)', 1100, 157, NULL, 'A medically designed pacifier that supports healthy dental development.\r\nMade from BPA-free silicone gentle on gums and mouth.\r\nShaped to fit comfortably without slipping.\r\nVented shield reduces skin irritation due to drool.\r\nSuitable for babies 0–18 months.', '2026-01-20 00:35:59', 500, 0),
(100, 'Lightweight Windbreaker', 6870, 2, NULL, 'Material: Nylon wind-resistant fabric with breathable mesh lining\r\nStyle: Sporty and functional, perfect for travel or running\r\nSeason: Spring and autumn\r\nRecommended age: 15–45', '2026-01-20 00:36:47', 40, 0),
(101, 'Wool Blend Overcoat', 12250, 2, NULL, 'Material: Premium wool–polyester blend for warmth and structure\r\nStyle: Formal or business wear, elegant long-coat design\r\nSeason: Autumn and winter\r\nRecommended age: 22–55', '2026-01-20 00:37:56', 20, 0),
(102, 'Slim Fit Chino Pants', 5250, 2, NULL, 'Material: Stretch cotton twill for comfort and mobility\r\nStyle: Smart casual; suitable for office, school, or daily wear\r\nSeason: All seasons\r\nRecommended age: 18–45', '2026-01-20 00:38:54', 50, 0),
(103, 'Adjustable High Chair', 12800, 160, NULL, 'This baby high chair can be adjusted in height to match your child’s growth.\r\nIts stable structure ensures safe use during mealtime and playtime.\r\nThe foldable design makes storage and cleaning easy.\r\nSuitable from the weaning stage through early childhood.', '2026-01-20 00:39:12', 100, 0),
(104, 'Athletic Jogger Pants', 4880, 2, NULL, 'Material: Polyester–spandex blend with moisture-wicking technology\r\nStyle: Sporty and comfortable; ideal for running, gym, or casual outings\r\nSeason: Spring, summer, and autumn\r\nRecommended age: 15–35', '2026-01-20 00:39:47', 50, 0),
(105, 'Cargo Utility Pants', 8370, 2, NULL, 'Material: Heavy-duty cotton canvas with reinforced stitching\r\nStyle: Outdoor and functional; multiple pockets for utility\r\nSeason: Autumn and winter\r\nRecommended age: 18–40', '2026-01-20 00:40:29', 50, 0),
(106, 'Ergonomic Baby Carrier', 7800, 160, NULL, 'This baby carrier is ergonomically designed to reduce strain on the shoulders and waist.\r\nBreathable cushioned materials provide comfort even during long use.\r\nSupports both front-carry and back-carry positions.\r\nIdeal for outings and travel.', '2026-01-20 00:40:34', 510, 0),
(108, 'Classic Straight Jeans', 5370, 2, NULL, 'Material: 100% durable denim cotton\r\nStyle: Timeless straight-leg design, suitable for everyday wear\r\nSeason: All seasons\r\nRecommended age: 18–50', '2026-01-20 00:42:10', 50, 0),
(109, 'Slim Fit Stretch Jeans', 5870, 2, NULL, 'Material: Cotton–elastane blend for flexibility and comfort\r\nStyle: Modern slim fit, ideal for casual and smart-casual outfits\r\nSeason: Spring, autumn, and winter\r\nRecommended age: 18–40', '2026-01-20 00:42:58', 40, 0),
(110, 'Foldable Baby Stroller', 18900, 160, NULL, 'A lightweight stroller that folds into a compact size for easy storage and transport.\r\nEquipped with safety belts and stable wheels for secure use.\r\nIncludes a sunshade to protect your baby when outdoors.\r\nSuitable for daily use and travel.', '2026-01-20 00:43:45', 510, 0),
(112, 'Rear-Facing Car Seat', 24800, 160, NULL, 'The rear-facing design protects the baby’s head and neck effectively.\r\nShock-absorbing structure improves safety during sudden braking.\r\nThick cushioning ensures comfort even for long rides.\r\nSuitable for newborns through toddlers.', '2026-01-20 00:44:51', 610, 0),
(113, 'Distressed Street Jeans', 6580, 2, NULL, 'Material: Premium denim with reinforced distressed detailing\r\nStyle: Streetwear fashion with a bold, edgy appearance\r\nSeason: Spring, summer, and autumn\r\nRecommended age: 16–35', '2026-01-20 00:45:22', 30, 0),
(114, 'Relaxed Linen Pants', 4750, 2, NULL, 'Material: Breathable linen–cotton blend\r\nStyle: Loose and comfortable; perfect for travel or warm-climate wear\r\nSeason: Spring and summer\r\nRecommended age: 20–55', '2026-01-20 00:47:16', 30, 0),
(115, 'Relaxed Fit Vintage Jeans', 5780, 2, NULL, 'Material: Heavyweight washed denim for a retro look\r\nStyle: Loose and comfortable, inspired by vintage fashion\r\nSeason: All seasons\r\nRecommended age: 20–55', '2026-01-20 00:47:54', 50, 0),
(116, 'Cotton Graphic Top', 1200, 158, NULL, 'Made from 100% breathable cotton for all-day comfort.\r\nCute graphic prints remain vibrant even after multiple washes.\r\nSoft stitching avoids itching at neckline and sleeves.\r\nPerfect for school, playtime, and outings.\r\nAvailable in multiple fun designs.', '2026-01-20 00:48:37', 1000, 0),
(117, 'Casual Cotton Shorts', 3780, 2, NULL, 'Material: Soft cotton twill with breathable texture\r\nStyle: Casual everyday wear, ideal for home or outdoor activities\r\nSeason: Spring and summer\r\nRecommended age: 16–45', '2026-01-20 00:48:53', 30, 0),
(118, 'Athletic Training Shorts', 2870, 2, NULL, 'Material: Lightweight polyester with moisture-wicking technology\r\nStyle: Sporty and functional, suitable for gym, running, and training\r\nSeason: Summer\r\nRecommended age: 14–35', '2026-01-20 00:49:51', 30, 0),
(119, 'Soft Stretch Pants', 1800, 158, NULL, 'These pants feature ultra-soft fabric with flexible stretch for active movements.\r\nElastic waistband ensures secure and gentle fit.\r\nKnee-reinforced panels prevent rapid wear and tear.\r\nGreat for playground activities and daily schoolwear.\r\nEasy to wash and maintain.', '2026-01-20 00:50:25', 800, 0),
(120, 'Denim Shorts Men', 3250, 2, NULL, 'Material: Durable denim cotton with light stretch\r\nStyle: Casual street style, perfect for daily wear\r\nSeason: Spring and summer\r\nRecommended age: 18–40', '2026-01-20 00:50:46', 40, 0),
(121, 'Cargo Utility Shorts', 3580, 2, NULL, 'Material: Heavy-duty cotton canvas with multiple utility pockets\r\nStyle: Outdoor and travel-friendly, practical and rugged\r\nSeason: Spring and summer\r\nRecommended age: 18–50', '2026-01-20 00:52:06', 40, 0),
(122, 'Classic Cotton Briefs', 580, 37, NULL, 'Classic Cotton Briefs', '2026-01-20 00:52:56', 99, 0),
(123, 'Boxer Shorts', 560, 37, NULL, 'Material: Cotton–poly blend with a smooth finish\r\nStyle: Loose-fit boxer style, comfortable for daily wear\r\nSeason: All seasons\r\nRecommended age: 18–50', '2026-01-20 00:53:31', 100, 0),
(124, 'Performance Boxer Briefs', 880, 37, NULL, 'Material: Moisture-wicking microfiber with stretch\r\nStyle: Athletic fit, designed for active lifestyles\r\nSeason: All seasons\r\nRecommended age: 15–40', '2026-01-20 00:54:14', 99, 0),
(125, 'Thermal Underwear Set', 2950, 37, NULL, 'Material: Heat-retaining polyester blend with soft inner lining\r\nStyle: Warm base layer, ideal for cold environments\r\nSeason: Winter\r\nRecommended age: 18–60', '2026-01-20 00:55:04', 100, 0),
(126, 'Floral Casual Dress', 2400, 158, NULL, 'Lightweight cotton dress with charming floral patterns.\r\nSmooth inner lining prevents itchiness.\r\nIdeal for birthdays, holidays, or photoshoots.\r\nBreathable fabric keeps kids comfortable in warm weather.\r\nWash-resistant prints maintain color brightness.', '2026-01-20 00:56:56', 250, 0),
(127, 'Soft Fit Basic Tee', 3470, 38, NULL, 'Material: Premium cotton with a smooth, breathable finish\r\nStyle: Casual everyday wear, flattering slim fit\r\nSeason: Spring and summer\r\nRecommended age: 16–45', '2026-01-20 00:58:24', 20, 0),
(128, 'Puffer Jacket (Winter)', 4500, 158, NULL, 'Thick padded insulation provides extra warmth during cold seasons.\r\nWater-resistant shell keeps rain and snow away.\r\nSoft fleece inner layer ensures comfort.\r\nLightweight despite heavy warmth rating.\r\nIdeal for winter school commutes and outdoor activities.', '2026-01-20 00:58:44', 500, 0),
(129, 'Ribbed Crop Top', 3650, 38, NULL, 'Material: Cotton–spandex ribbed fabric with stretch\r\nStyle: Trendy and modern, perfect for casual or street outfits\r\nSeason: Spring and summer\r\nRecommended age: 16–30', '2026-01-20 00:59:12', 40, 0),
(130, 'Cozy Sleepwear Set (2 pcs)', 2100, 158, NULL, 'Made with skin-friendly fabric designed for long, comfortable sleep.\r\nElastic cuffs prevent sleeves from sliding up.\r\nBreathable, non-sweat design for temperature regulation.\r\nCute print encourages kids to enjoy bedtime.\r\nMachine-washable and durable.', '2026-01-20 00:59:48', 1000, 0),
(131, 'Elegant Blouse Top', 6280, 38, NULL, 'Material: Lightweight polyester–chiffon blend\r\nStyle: Smart casual or office wear, feminine and polished\r\nSeason: Spring, summer, and autumn\r\nRecommended age: 22–50', '2026-01-20 01:00:06', 30, 0),
(132, 'Long-Sleeve Modal Tee', 5290, 38, NULL, 'Material: Modal–cotton blend, ultra-soft and breathable\r\nStyle: Minimal casual, ideal for layering or solo wear\r\nSeason: Spring, autumn, and cool summer evenings\r\nRecommended age: 20–55', '2026-01-20 01:00:49', 30, 0),
(133, 'STEM Learning Board', 2900, 159, NULL, 'A multi-activity board encouraging early math, writing, and shape recognition.\r\nBoosts problem-solving and cognitive skills.\r\nNon-toxic materials ensure safe learning playtime.\r\nPerfect gift for preschoolers.\r\nPromotes independent play and family learning sessions.', '2026-01-20 01:01:40', 10000, 0),
(134, 'Casual Cotton Dress', 8720, 167, NULL, 'Material: Soft cotton blend with breathable fibers\r\nStyle: Casual everyday dress, comfortable and easy to wear\r\nSeason: Spring and summer\r\nRecommended age: 16–45', '2026-01-20 01:01:56', 20, 0),
(135, 'Extra-Plush Soft Toy Bear', 1300, 159, NULL, 'This fluffy bear is crafted from hypoallergenic plush material.\r\nIdeal comfort toy for sleeping or travel.\r\nStitched tightly to avoid loose fur or buttons.\r\nHuggable size and friendly expression ease bedtime.\r\nSafe for newborns and toddlers.', '2026-01-20 01:02:29', 1000, 0),
(136, 'Elegant A-Line Dress', 8380, 39, NULL, 'Material: Polyester–chiffon blend with smooth inner lining\r\nStyle: Elegant and feminine, suitable for office or formal events\r\nSeason: Spring, summer, and autumn\r\nRecommended age: 22–50', '2026-01-20 01:02:48', 30, 0),
(137, 'Building Blocks Mega Set', 3500, 159, NULL, 'Large 100-piece set helps kids develop motor and creative thinking skills.\r\nRounded edges prevent hand injury.\r\nBright colors attract attention and enhance color learning.\r\nCompatible with major block brands.\r\nPerfect for cooperative play and brain development.', '2026-01-20 01:03:21', 500, 0),
(138, 'Floral Maxi Dress', 7350, 39, NULL, 'Material: Lightweight rayon fabric with floral pattern\r\nStyle: Relaxed and stylish, perfect for vacations and outings\r\nSeason: Summer\r\nRecommended age: 18–40', '2026-01-20 01:03:28', 20, 0),
(139, 'Car & Character Figures Set', 1900, 159, NULL, '1900', '2026-01-20 01:04:07', 1000, 0),
(140, 'Knit Long-Sleeve Dress', 10320, 39, NULL, 'Material: Stretch knit fabric with soft texture\r\nStyle: Modern casual, ideal for cooler weather\r\nSeason: Autumn and winter\r\nRecommended age: 25–55', '2026-01-20 01:04:20', 20, 0),
(141, 'Cozy Fleece Hoodie', 5780, 40, NULL, 'Material: Soft cotton–poly fleece blend with warm inner lining\r\nStyle: Casual everyday wear, relaxed and comfortable\r\nSeason: Autumn and winter\r\nRecommended age: 16–45', '2026-01-20 01:05:37', 30, 0),
(142, 'Lightweight Knit Sweater', 5470, 40, NULL, 'Material: Fine acrylic–cotton knit, breathable and soft\r\nStyle: Minimal casual, elegant and easy to layer\r\nSeason: Spring and autumn\r\nRecommended age: 20–55', '2026-01-20 01:06:23', 30, 0),
(143, 'Baking Trays', 1500, 125, NULL, 'Non-stick carbon-steel trays provide even heating for cookies, pastries, and roasting. Reinforced construction prevents warping, ensuring long-term performance.', '2026-01-20 01:06:53', 1000, 0),
(144, 'Oversized Street Hoodie', 4790, 40, NULL, 'Material: Heavyweight cotton fleece, soft and durable\r\nStyle: Streetwear oversized fit, trendy and youthful\r\nSeason: All seasons, especially cool evenings\r\nRecommended age: 15–35', '2026-01-20 01:07:06', 30, 0),
(145, 'Mixing Bowls', 1800, 89, NULL, 'tainless steel, glass, or premium plastic bowls designed for whisking, folding, marinating, and mixing. Nesting sets save space and resist stains and odors.', '2026-01-20 01:10:46', 1000, 0),
(146, 'Baking Trays', 1800, 89, NULL, '-Non-stick carbon-steel trays provide even heating for cookies, pastries, and roasting. Reinforced construction prevents warping, ensuring long-term performance.', '2026-01-20 01:11:38', 999, 0),
(147, 'Measuring Cups', 600, 89, NULL, 'Accurate and easy-to-read measuring cups ensure consistent results in cooking and baking. Available in stainless steel, plastic, and clear glass.', '2026-01-20 01:12:44', 800, 0),
(148, 'Cake Molds', 600, 89, NULL, 'Durable non-stick molds in multiple shapes allow clean release and perfect cake form. Suitable for home bakers and professionals.', '2026-01-20 01:13:57', 1000, 0),
(149, 'Cookie Cutters', 200, 89, NULL, 'Stainless-steel or BPA-free plastic cutters create fun shapes for themed cookies and decorations. Easy to clean and durable for repeated use.', '2026-01-20 01:14:50', 999, 0),
(150, 'Elegant Cardigan Sweater', 5270, 40, NULL, 'Material: Wool–poly blend with smooth texture\r\nStyle: Smart casual, suitable for office or daily wear\r\nSeason: Autumn and winter\r\nRecommended age: 25–60', '2026-01-20 01:17:07', 16, 0),
(151, 'Casual Denim Jacket', 5690, 41, NULL, 'Material: 100% durable denim cotton\r\nStyle: Casual street style, easy to layer with everyday outfits\r\nSeason: Spring, autumn, and mild winter\r\nRecommended age: 16–45', '2026-01-20 02:00:20', 23, 0),
(152, 'ダンベル', 2500, 36, NULL, '梱包サイズ:41 × 14 × 14 cm (16.1" × 5.5" × 5.5")\r\n製品の色: 図に示すように\r\n製品材質:ABS\r\n製品の特徴:\r\n毎日のトレーニングを素早く行うためのフィットファイター ウェイト\r\nこれらの革新的な振動ウェイトを使用して、毎日わずか 6 分間の有酸素運動を行うだけで、目に見える結果が得られます。首の緊張を和らげ、形を整え、全体的な健康をサポートするのに役立ちます。\r\n', '2026-01-23 00:26:08', 90, 0),
(157, 'マテリアル', 1800, 6, NULL, 'ゴムコーティングされたスチール製ダンベルヘッド\r\nADVANTAGE\r\nしっかりとしたダンベルを1つ聞いて、決して緩めず、カラフルなロゴを作ります\r\n仕様\r\n2.5kg ～ 50kg、2.5kg 増加\r\nロゴ\r\n顧客のロゴ\r\nパッケージ\r\nビニール袋 - 紙パック - パレットまたは木製ケース\r\n配達時間\r\nデポジットの受領後 18 ～ 25 日\r\nMOQ（最小注文数量）\r\n2トン\r\n供給能力\r\n500トン/週あたりのトン数', '2026-01-23 00:33:28', 70, 0),
(161, '懸念される化m b学ｈｊ物質 なし', 2000, 36, NULL, 'トレーニングサイト\r\nARMS,バック\r\n適用\r\nゴム製ひもの箱の開発者\r\n部署名\r\n男女兼用\r\nタイプ の スポーツ\r\n筋力トレーニング\r\n起源\r\nCn (原点)', '2026-01-23 00:37:04', 56, 0),
(162, 'Eco-Friendly C Bag', 1800, 50, NULL, 'This reusable tote bag is crafted from durable, eco-friendly cotton canvas.\r\nIts large capacity makes it perfect for groceries, textbooks, laptops, or daily essentials.\r\nThe reinforced bottom panel prevents sagging, ensuring stability even with heavier items.\r\nPrinted graphics use non-toxic ink, making the bag environmentally safe.\r\nIdeal for eco-conscious customers seeking a stylish and sustainable option.', '2026-01-23 00:47:19', 999, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(512) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `created_at`) VALUES
(1, 1, 'vneck2.jpg', 1, '2025-12-11 04:10:22'),
(2, 2, 'vneck.jpg', 1, '2025-12-11 04:10:22'),
(3, 3, 'product02.jpg', 1, '2025-12-11 04:10:22'),
(4, 5, '2005-2.jpg', 1, '2025-12-11 04:10:22'),
(5, 6, '7154.jpg', 1, '2025-12-11 04:10:22'),
(8, 10, '1765503992_pc.jpg', 1, '2025-12-12 01:46:32'),
(9, 10, '1765503992_ppc.jpg', 0, '2025-12-12 01:46:32'),
(13, 11, '1765951374_ppc.jpg', 1, '2025-12-17 06:02:54'),
(14, 11, '1766117540_7193.jpg', 0, '2025-12-19 04:12:20'),
(16, 17, '1766121056_1931.jpeg', 1, '2025-12-19 05:10:56'),
(19, 18, '1768789042_4883.png', 1, '2026-01-19 02:17:22'),
(20, 20, '1768789267_1935.png', 1, '2026-01-19 02:21:07'),
(21, 22, '1768789406_9626.png', 1, '2026-01-19 02:23:26'),
(23, 32, '1768791242_2072.jpg', 1, '2026-01-19 02:54:02'),
(24, 37, '1768791418_6229.jpg', 1, '2026-01-19 02:56:58'),
(25, 38, '1768791558_5125.jpg', 1, '2026-01-19 02:59:18'),
(26, 39, '1768791563_4694.jpg', 1, '2026-01-19 02:59:23'),
(27, 40, '1768791810_9681.jpg', 1, '2026-01-19 03:03:30'),
(28, 41, '1768791836_8754.jpg', 1, '2026-01-19 03:03:56'),
(29, 42, '1768791956_2027.jpg', 1, '2026-01-19 03:05:56'),
(30, 43, '1768792029_9294.jpg', 1, '2026-01-19 03:07:09'),
(32, 45, '1768792283_3389.jpg', 1, '2026-01-19 03:11:23'),
(33, 46, '1768792287_7503.png', 1, '2026-01-19 03:11:27'),
(34, 44, '1768792316_8872.png', 0, '2026-01-19 03:11:56'),
(36, 48, '1768792441_7987.png', 1, '2026-01-19 03:14:01'),
(37, 27, '1768792507_2315.png', 0, '2026-01-19 03:15:07'),
(38, 49, '1768792696_9751.jpg', 1, '2026-01-19 03:18:16'),
(39, 50, '1768792751_2350.png', 1, '2026-01-19 03:19:11'),
(41, 51, '1768792920_1868.png', 1, '2026-01-19 03:22:00'),
(42, 52, '1768792945_7597.jpg', 1, '2026-01-19 03:22:25'),
(43, 53, '1768793632_2565.jpg', 1, '2026-01-19 03:33:52'),
(44, 54, '1768793755_2230.jpg', 1, '2026-01-19 03:35:55'),
(45, 55, '1768793828_4439.jpg', 1, '2026-01-19 03:37:08'),
(46, 56, '1768794082_3398.jpg', 1, '2026-01-19 03:41:22'),
(48, 58, '1768794322_7554.jpg', 0, '2026-01-19 03:45:22'),
(49, 59, '1768794408_3887.jpg', 1, '2026-01-19 03:46:48'),
(50, 60, '1768794488_8500.jpg', 1, '2026-01-19 03:48:08'),
(51, 61, '1768794649_1762.jpg', 1, '2026-01-19 03:50:49'),
(52, 62, '1768794755_6400.jpg', 1, '2026-01-19 03:52:35'),
(53, 63, '1768794905_6665.jpg', 1, '2026-01-19 03:55:05'),
(54, 64, '1768795009_9180.webp', 1, '2026-01-19 03:56:49'),
(55, 65, '1768795036_9726.jpg', 1, '2026-01-19 03:57:16'),
(56, 66, '1768867293_8640.png', 1, '2026-01-20 00:01:33'),
(57, 67, '1768867352_5370.png', 1, '2026-01-20 00:02:32'),
(58, 68, '1768867426_1725.png', 1, '2026-01-20 00:03:46'),
(59, 69, '1768867484_6968.png', 1, '2026-01-20 00:04:44'),
(60, 70, '1768867582_1758.png', 1, '2026-01-20 00:06:22'),
(61, 71, '1768867642_8277.png', 1, '2026-01-20 00:07:22'),
(62, 72, '1768867690_2288.webp', 1, '2026-01-20 00:08:10'),
(63, 73, '1768867700_3069.png', 1, '2026-01-20 00:08:20'),
(64, 74, '1768867819_8365.webp', 1, '2026-01-20 00:10:19'),
(65, 75, '1768867853_5586.png', 1, '2026-01-20 00:10:53'),
(66, 76, '1768867909_6478.png', 1, '2026-01-20 00:11:49'),
(67, 77, '1768867943_3099.webp', 1, '2026-01-20 00:12:23'),
(68, 78, '1768868030_2690.png', 1, '2026-01-20 00:13:50'),
(69, 79, '1768868062_9259.webp', 1, '2026-01-20 00:14:22'),
(70, 80, '1768868123_3907.png', 1, '2026-01-20 00:15:23'),
(71, 81, '1768868236_7972.jpg', 1, '2026-01-20 00:17:16'),
(72, 82, '1768868373_5080.jpg', 1, '2026-01-20 00:19:33'),
(73, 83, '1768868482_6037.png', 1, '2026-01-20 00:21:22'),
(74, 84, '1768868526_2574.jpg', 1, '2026-01-20 00:22:06'),
(75, 85, '1768868537_7329.png', 1, '2026-01-20 00:22:17'),
(76, 86, '1768868598_9473.png', 1, '2026-01-20 00:23:18'),
(77, 87, '1768868687_3791.jpg', 1, '2026-01-20 00:24:47'),
(78, 88, '1768868914_7117.jpg', 1, '2026-01-20 00:28:34'),
(79, 89, '1768869020_5674.jpg', 1, '2026-01-20 00:30:20'),
(80, 90, '1768869080_6995.jpg', 1, '2026-01-20 00:31:20'),
(81, 91, '1768869134_7009.jpg', 1, '2026-01-20 00:32:14'),
(82, 92, '1768869163_3167.jpg', 1, '2026-01-20 00:32:43'),
(83, 93, '1768869185_3677.jpg', 1, '2026-01-20 00:33:05'),
(84, 94, '1768869205_3308.jpg', 1, '2026-01-20 00:33:25'),
(85, 97, '1768869291_3432.jpg', 1, '2026-01-20 00:34:51'),
(86, 98, '1768869306_8602.jpg', 1, '2026-01-20 00:35:06'),
(87, 99, '1768869359_9239.jpg', 1, '2026-01-20 00:35:59'),
(88, 100, '1768869407_1090.jpg', 1, '2026-01-20 00:36:47'),
(89, 101, '1768869476_8489.jpg', 1, '2026-01-20 00:37:56'),
(90, 102, '1768869534_4775.jpg', 1, '2026-01-20 00:38:54'),
(91, 103, '1768869552_9322.jpg', 1, '2026-01-20 00:39:12'),
(92, 104, '1768869587_7721.jpg', 1, '2026-01-20 00:39:47'),
(93, 105, '1768869629_6676.jpg', 1, '2026-01-20 00:40:29'),
(94, 106, '1768869634_8227.jpg', 1, '2026-01-20 00:40:34'),
(96, 108, '1768869730_1727.jpg', 1, '2026-01-20 00:42:10'),
(97, 109, '1768869778_6838.jpg', 1, '2026-01-20 00:42:58'),
(98, 110, '1768869825_5082.jpg', 1, '2026-01-20 00:43:45'),
(100, 112, '1768869891_2161.jpg', 1, '2026-01-20 00:44:51'),
(101, 113, '1768869922_7260.jpg', 1, '2026-01-20 00:45:22'),
(102, 114, '1768870036_4796.jpg', 1, '2026-01-20 00:47:16'),
(103, 115, '1768870074_1057.jpg', 1, '2026-01-20 00:47:54'),
(104, 117, '1768870133_2745.jpg', 1, '2026-01-20 00:48:53'),
(105, 116, '1768870142_8421.jpg', 0, '2026-01-20 00:49:02'),
(106, 118, '1768870191_7670.jpg', 1, '2026-01-20 00:49:51'),
(107, 119, '1768870225_7175.jpg', 1, '2026-01-20 00:50:25'),
(108, 120, '1768870246_5798.jpg', 1, '2026-01-20 00:50:46'),
(109, 121, '1768870326_6322.jpg', 1, '2026-01-20 00:52:06'),
(110, 122, '1768870376_9073.jpg', 1, '2026-01-20 00:52:56'),
(111, 123, '1768870411_7742.jpg', 1, '2026-01-20 00:53:31'),
(112, 124, '1768870454_9983.jpg', 1, '2026-01-20 00:54:14'),
(113, 125, '1768870504_2988.jpg', 1, '2026-01-20 00:55:04'),
(114, 126, '1768870616_7036.jpg', 1, '2026-01-20 00:56:56'),
(115, 127, '1768870704_4818.jpg', 1, '2026-01-20 00:58:24'),
(116, 128, '1768870724_1909.jpg', 1, '2026-01-20 00:58:44'),
(117, 129, '1768870752_9611.jpg', 1, '2026-01-20 00:59:12'),
(118, 130, '1768870788_9320.jpg', 1, '2026-01-20 00:59:48'),
(119, 131, '1768870806_3172.jpg', 1, '2026-01-20 01:00:06'),
(120, 132, '1768870849_9481.jpg', 1, '2026-01-20 01:00:49'),
(121, 133, '1768870900_5085.jpg', 1, '2026-01-20 01:01:40'),
(122, 134, '1768870916_5580.jpg', 1, '2026-01-20 01:01:56'),
(123, 135, '1768870949_9402.jpg', 1, '2026-01-20 01:02:29'),
(124, 136, '1768870968_6792.jpg', 1, '2026-01-20 01:02:48'),
(125, 137, '1768871001_9916.jpg', 1, '2026-01-20 01:03:21'),
(126, 138, '1768871008_7631.jpg', 1, '2026-01-20 01:03:28'),
(127, 139, '1768871047_1001.jpg', 1, '2026-01-20 01:04:07'),
(128, 140, '1768871060_7020.jpg', 1, '2026-01-20 01:04:20'),
(129, 141, '1768871137_7445.jpg', 1, '2026-01-20 01:05:37'),
(130, 142, '1768871183_6107.jpg', 1, '2026-01-20 01:06:23'),
(131, 144, '1768871226_2483.jpg', 1, '2026-01-20 01:07:06'),
(132, 143, '1768871232_6271.jpg', 0, '2026-01-20 01:07:12'),
(133, 145, '1768871446_2781.jpg', 1, '2026-01-20 01:10:46'),
(134, 146, '1768871498_6895.jpg', 1, '2026-01-20 01:11:38'),
(135, 147, '1768871564_8426.jpg', 1, '2026-01-20 01:12:44'),
(136, 148, '1768871637_8645.jpg', 1, '2026-01-20 01:13:57'),
(137, 149, '1768871690_7225.jpg', 1, '2026-01-20 01:14:50'),
(138, 150, '1768871827_9298.jpg', 1, '2026-01-20 01:17:07'),
(139, 151, '1768874420_1258.jpg', 1, '2026-01-20 02:00:20'),
(143, 152, '1769128202_6445.avif', 0, '2026-01-23 00:30:02'),
(144, 152, '1769128202_6732.avif', 0, '2026-01-23 00:30:02'),
(145, 152, '1769128202_4667.avif', 0, '2026-01-23 00:30:02'),
(146, 157, '1769128409_7641.avif', 1, '2026-01-23 00:33:29'),
(147, 157, '1769128409_6952.avif', 0, '2026-01-23 00:33:29'),
(148, 157, '1769128409_8444.avif', 0, '2026-01-23 00:33:29'),
(149, 18, '1769128482_3502.png', 0, '2026-01-23 00:34:42'),
(156, 161, '1769128624_6672.avif', 1, '2026-01-23 00:37:04'),
(157, 161, '1769128624_2941.avif', 0, '2026-01-23 00:37:04'),
(158, 161, '1769128624_4378.avif', 0, '2026-01-23 00:37:04'),
(159, 162, '1769129239_2822.jpg', 0, '2026-01-23 00:47:19'),
(160, 162, '1769129287_6814.png', 1, '2026-01-23 00:48:07');

-- --------------------------------------------------------

--
-- テーブルの構造 `product_likes`
--

CREATE TABLE `product_likes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `product_likes`
--

INSERT INTO `product_likes` (`id`, `product_id`, `user_id`, `created_at`) VALUES
(4, 10, 7, '2025-12-19 03:10:19'),
(6, 17, 7, '2025-12-22 02:38:51'),
(7, 162, 3, '2026-01-26 00:37:17'),
(8, 161, 3, '2026-02-09 03:37:32');

-- --------------------------------------------------------

--
-- テーブルの構造 `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `is_hidden`) VALUES
(4, 17, 7, 0, 'いいね', '2025-12-22 00:53:29', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `product_stock_history`
--

CREATE TABLE `product_stock_history` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `old_stock` int(11) DEFAULT NULL,
  `new_stock` int(11) DEFAULT NULL,
  `change_value` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `product_tags`
--

CREATE TABLE `product_tags` (
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `product_tags`
--

INSERT INTO `product_tags` (`product_id`, `tag_id`) VALUES
(17, 187),
(17, 193),
(17, 200),
(17, 224),
(17, 226),
(17, 228),
(17, 229),
(17, 233),
(17, 236),
(18, 186),
(18, 206),
(18, 225),
(18, 229),
(18, 230),
(20, 186),
(20, 206),
(20, 223),
(20, 225),
(20, 227),
(20, 228),
(20, 229),
(20, 230),
(20, 236),
(20, 237),
(22, 186),
(22, 193),
(22, 200),
(22, 206),
(22, 223),
(22, 224),
(22, 229),
(22, 233),
(22, 236),
(22, 237),
(27, 224),
(27, 230),
(27, 232),
(27, 234),
(27, 235),
(32, 209),
(32, 210),
(32, 215),
(37, 209),
(37, 215),
(37, 222),
(38, 192),
(39, 186),
(39, 209),
(39, 215),
(40, 187),
(40, 209),
(40, 210),
(40, 215),
(40, 222),
(41, 196),
(41, 202),
(42, 196),
(42, 204),
(43, 208),
(43, 209),
(43, 210),
(43, 215),
(45, 192),
(45, 196),
(45, 204),
(49, 196),
(49, 204),
(52, 192),
(52, 196),
(52, 204),
(53, 196),
(53, 204),
(54, 192),
(54, 196),
(54, 204),
(55, 192),
(55, 196),
(55, 204),
(56, 186),
(56, 202),
(58, 202),
(59, 208),
(59, 209),
(59, 215),
(60, 202),
(61, 208),
(61, 209),
(61, 215),
(62, 202),
(63, 202),
(64, 202),
(65, 209),
(65, 215),
(65, 222),
(72, 202),
(74, 202),
(77, 202),
(79, 202),
(81, 194),
(82, 194),
(84, 194),
(86, 194),
(87, 194),
(88, 186),
(88, 215),
(88, 222),
(89, 186),
(89, 215),
(90, 186),
(90, 215),
(91, 186),
(91, 215),
(91, 222),
(93, 215),
(93, 222),
(97, 186),
(97, 215),
(100, 186),
(100, 215),
(101, 186),
(101, 215),
(102, 186),
(102, 215),
(104, 186),
(104, 215),
(105, 186),
(105, 215),
(108, 186),
(108, 215),
(109, 186),
(109, 215),
(113, 186),
(113, 215),
(114, 186),
(114, 215),
(115, 186),
(115, 215),
(117, 186),
(117, 215),
(118, 186),
(118, 209),
(118, 215),
(120, 186),
(120, 209),
(120, 215),
(121, 186),
(121, 209),
(121, 215),
(122, 186),
(122, 215),
(123, 186),
(123, 215),
(124, 186),
(124, 215),
(125, 186),
(125, 215),
(127, 186),
(127, 209),
(127, 216),
(128, 186),
(128, 187),
(128, 193),
(128, 221),
(128, 228),
(128, 232),
(128, 251),
(128, 264),
(128, 265),
(128, 266),
(128, 267),
(128, 268),
(128, 269),
(128, 270),
(128, 272),
(128, 273),
(128, 274),
(129, 186),
(129, 209),
(129, 216),
(131, 186),
(131, 209),
(131, 216),
(132, 186),
(132, 209),
(132, 216),
(134, 186),
(135, 186),
(135, 187),
(135, 193),
(135, 221),
(135, 228),
(135, 264),
(135, 265),
(135, 267),
(135, 268),
(135, 270),
(135, 272),
(135, 273),
(135, 274),
(136, 186),
(136, 216),
(138, 186),
(138, 216),
(140, 186),
(140, 216),
(141, 186),
(141, 216),
(142, 186),
(142, 216),
(143, 186),
(143, 193),
(143, 199),
(143, 240),
(143, 243),
(143, 245),
(143, 247),
(143, 248),
(144, 186),
(144, 216),
(148, 186),
(148, 187),
(148, 193),
(148, 199),
(148, 221),
(148, 232),
(148, 238),
(148, 239),
(148, 240),
(148, 241),
(148, 242),
(148, 243),
(148, 244),
(148, 245),
(148, 246),
(148, 247),
(148, 248),
(149, 186),
(149, 187),
(149, 193),
(149, 199),
(149, 221),
(149, 232),
(149, 238),
(149, 239),
(149, 240),
(149, 241),
(149, 242),
(149, 243),
(149, 244),
(149, 245),
(149, 246),
(149, 247),
(149, 248),
(150, 186),
(150, 187),
(150, 206),
(150, 207),
(150, 208),
(150, 209),
(150, 210),
(150, 211),
(150, 212),
(150, 213),
(150, 214),
(150, 216),
(150, 218),
(150, 219),
(150, 220),
(150, 221),
(150, 222),
(151, 186),
(151, 216),
(151, 222),
(162, 186),
(162, 187),
(162, 193),
(162, 200),
(162, 206),
(162, 223),
(162, 224),
(162, 225),
(162, 226),
(162, 227),
(162, 228),
(162, 229),
(162, 230),
(162, 231),
(162, 232),
(162, 233),
(162, 234),
(162, 235),
(162, 236),
(162, 237);

-- --------------------------------------------------------

--
-- テーブルの構造 `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Received','Refunded') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_date` timestamp NULL DEFAULT NULL,
  `canceled` tinyint(1) DEFAULT '0',
  `admin_note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `product_id`, `user_id`, `reason`, `image`, `status`, `request_date`, `processed_date`, `canceled`, `admin_note`) VALUES
(1, 30, 1, 7, 'Not as Described', '1764643693_2005-2.jpg', 'Refunded', '2025-12-02 02:48:13', NULL, 0, ''),
(2, 12, 2, 7, 'Damaged', '1764644004_product03.jpg', 'Refunded', '2025-12-02 02:53:24', NULL, 0, 'hgjhgjkh'),
(3, 34, 6, 7, 'Wrong Item', '', 'Pending', '2025-12-08 00:47:03', NULL, 0, 'ダメ'),
(4, 42, 17, 7, 'Wrong Item', '1766372008_casr.jpg', 'Rejected', '2025-12-22 02:53:28', NULL, 0, '');

-- --------------------------------------------------------

--
-- テーブルの構造 `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`) VALUES
(186, 'New', 'new', '2025-12-17 06:08:55'),
(187, 'Popular', 'popular', '2025-12-17 06:08:55'),
(188, 'Best Seller', 'bestseller', '2025-12-17 06:08:55'),
(189, 'Laptop', 'laptop', '2025-12-17 06:08:55'),
(190, 'Gaming', 'gaming', '2025-12-17 06:08:55'),
(191, 'Business', 'business', '2025-12-17 06:08:55'),
(192, 'Office', 'office', '2025-12-17 06:08:55'),
(193, 'Lightweight', 'lightweight', '2025-12-17 06:08:55'),
(194, 'High Performance', 'high-performance', '2025-12-17 06:08:55'),
(195, 'Battery Life', 'battery-life', '2025-12-17 06:08:55'),
(196, 'SSD', 'ssd', '2025-12-17 06:08:55'),
(197, 'Touchscreen', 'touchscreen', '2025-12-17 06:08:55'),
(198, 'Portable', 'portable', '2025-12-17 06:08:55'),
(199, 'Budget', 'budget', '2025-12-17 06:08:55'),
(200, 'Premium', 'premium', '2025-12-17 06:08:55'),
(201, 'Wireless', 'wireless', '2025-12-17 06:08:55'),
(202, 'Bluetooth', 'bluetooth', '2025-12-17 06:08:55'),
(203, 'Fast Charging', 'fast-charging', '2025-12-17 06:08:55'),
(204, '4K', '4k', '2025-12-17 06:08:55'),
(205, 'AI Powered', 'ai-powered', '2025-12-17 06:08:55'),
(206, 'Fashion', 'fashion', '2025-12-17 06:08:55'),
(207, 'Casual', 'casual', '2025-12-17 06:08:55'),
(208, 'Formal', 'formal', '2025-12-17 06:08:55'),
(209, 'Summer', 'summer', '2025-12-17 06:08:55'),
(210, 'Winter', 'winter', '2025-12-17 06:08:55'),
(211, 'Cotton', 'cotton', '2025-12-17 06:08:55'),
(212, 'Slim Fit', 'slim-fit', '2025-12-17 06:08:55'),
(213, 'Oversized', 'oversized', '2025-12-17 06:08:55'),
(214, 'Unisex', 'unisex', '2025-12-17 06:08:55'),
(215, 'Mens', 'mens', '2025-12-17 06:08:55'),
(216, 'Womens', 'womens', '2025-12-17 06:08:55'),
(217, 'Kids', 'kids', '2025-12-17 06:08:55'),
(218, 'Comfortable', 'comfortable', '2025-12-17 06:08:55'),
(219, 'Stretchable', 'stretchable', '2025-12-17 06:08:55'),
(220, 'Trendy', 'trendy', '2025-12-17 06:08:55'),
(221, 'Eco Friendly', 'eco-friendly', '2025-12-17 06:08:55'),
(222, 'Sale', 'sale', '2025-12-17 06:08:55'),
(223, 'Luxury', 'luxury', '2025-12-17 06:08:55'),
(224, 'Daily Use', 'daily-use', '2025-12-17 06:08:55'),
(225, 'Travel', 'travel', '2025-12-17 06:08:55'),
(226, 'Leather', 'leather', '2025-12-17 06:08:55'),
(227, 'Minimal', 'minimal', '2025-12-17 06:08:55'),
(228, 'Gift', 'gift', '2025-12-17 06:08:55'),
(229, 'Handmade', 'handmade', '2025-12-17 06:08:55'),
(230, 'Classic', 'classic', '2025-12-17 06:08:55'),
(231, 'Modern', 'modern', '2025-12-17 06:08:55'),
(232, 'Durable', 'durable', '2025-12-17 06:08:55'),
(233, 'Water Resistant', 'water-resistant', '2025-12-17 06:08:55'),
(234, 'Adjustable', 'adjustable', '2025-12-17 06:08:55'),
(235, 'Compact', 'compact', '2025-12-17 06:08:55'),
(236, 'Stylish', 'stylish', '2025-12-17 06:08:55'),
(237, 'Limited', 'limited', '2025-12-17 06:08:55'),
(238, 'Cookware', 'cookware', '2025-12-17 06:08:55'),
(239, 'Non Stick', 'non-stick', '2025-12-17 06:08:55'),
(240, 'Stainless Steel', 'stainless-steel', '2025-12-17 06:08:55'),
(241, 'Cast Iron', 'cast-iron', '2025-12-17 06:08:55'),
(242, 'Kitchen Tools', 'kitchen-tools', '2025-12-17 06:08:55'),
(243, 'Baking', 'baking', '2025-12-17 06:08:55'),
(244, 'Microwave Safe', 'microwave-safe', '2025-12-17 06:08:55'),
(245, 'Dishwasher Safe', 'dishwasher-safe', '2025-12-17 06:08:55'),
(246, 'Heat Resistant', 'heat-resistant', '2025-12-17 06:08:55'),
(247, 'Chef Grade', 'chef-grade', '2025-12-17 06:08:55'),
(248, 'Storage', 'storage', '2025-12-17 06:08:55'),
(249, 'Fitness', 'fitness', '2025-12-17 06:08:55'),
(250, 'Gym', 'gym', '2025-12-17 06:08:55'),
(251, 'Outdoor', 'outdoor', '2025-12-17 06:08:55'),
(252, 'Training', 'training', '2025-12-17 06:08:55'),
(253, 'Beginner', 'beginner', '2025-12-17 06:08:55'),
(254, 'Waterproof', 'waterproof', '2025-12-17 06:08:55'),
(255, 'Breathable', 'breathable', '2025-12-17 06:08:55'),
(256, 'Running', 'running', '2025-12-17 06:08:55'),
(257, 'Yoga', 'yoga', '2025-12-17 06:08:55'),
(258, 'Football', 'football', '2025-12-17 06:08:55'),
(259, 'Basketball', 'basketball', '2025-12-17 06:08:55'),
(260, 'Camping', 'camping', '2025-12-17 06:08:55'),
(261, 'Hiking', 'hiking', '2025-12-17 06:08:55'),
(262, 'High Grip', 'high-grip', '2025-12-17 06:08:55'),
(263, 'Performance', 'performance', '2025-12-17 06:08:55'),
(264, 'Baby Safe', 'baby-safe', '2025-12-17 06:08:55'),
(265, 'Kids Friendly', 'kids-friendly', '2025-12-17 06:08:55'),
(266, 'Educational', 'educational', '2025-12-17 06:08:55'),
(267, 'Soft', 'soft', '2025-12-17 06:08:55'),
(268, 'Non Toxic', 'non-toxic', '2025-12-17 06:08:55'),
(269, 'Colorful', 'colorful', '2025-12-17 06:08:55'),
(270, 'Fun', 'fun', '2025-12-17 06:08:55'),
(271, 'Learning', 'learning', '2025-12-17 06:08:55'),
(272, 'Washable', 'washable', '2025-12-17 06:08:55'),
(273, 'Age Appropriate', 'age-appropriate', '2025-12-17 06:08:55'),
(274, 'Trusted', 'trusted', '2025-12-17 06:08:55');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`) VALUES
(1, 'yeasir momen', 'r2437031@icb.ac.jp', '$2y$10$.X2WQ8UCrXVD2xVLE3atVuFz2yKHic3AU83yNY/nbUUQGEpOv38im', NULL, NULL),
(2, 'habib', 'r2437043@icb.ac.jp', '$2y$10$LMxTBgHR1ESsq9gYgg1dPeXqqrL6ECwpZuLqIWfh19YGHebqbTWS2', NULL, NULL),
(3, 'rinku', 'r2437045@icb.ac.jp', '$2y$10$c2x0YQiNpd2gf3mLnpjnVuG9M1T5XM19w5CvWdLe2yXLIYqY0gZ2C', NULL, NULL),
(7, 'rabin', 'r2437009@icb.ac.jp', '$2y$10$U2IG8.Zz0viOqcCY4m2HX.ugQYrgpEZDWg7mXzyjtLLuRvm6uHhOy', '神奈川県 横浜市神奈川区 入江', NULL),
(8, 'ou ', 'r2437001@icb.ac.jp', '$2y$10$Vd3hXBRg95Ps9VTc6mczq.LyMSQD5UT1TT6P25/EPlaSNzfUcv5kC', NULL, NULL),
(9, 'Nora is me', 'r2437021@icb.ac.jp', '$2y$10$W3y1EEeCKoI.3vByWlXClu20f6jIcj0zaWhVkTGUDiCayASLA.eNO', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_tags`
--
ALTER TABLE `category_tags`
  ADD PRIMARY KEY (`category_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_likes`
--
ALTER TABLE `product_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_like` (`product_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_stock_history`
--
ALTER TABLE `product_stock_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_tags`
--
ALTER TABLE `product_tags`
  ADD PRIMARY KEY (`product_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;
--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT for table `product_likes`
--
ALTER TABLE `product_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_stock_history`
--
ALTER TABLE `product_stock_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `category_tags`
--
ALTER TABLE `category_tags`
  ADD CONSTRAINT `category_tags_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_pi_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `product_likes`
--
ALTER TABLE `product_likes`
  ADD CONSTRAINT `product_likes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `product_stock_history`
--
ALTER TABLE `product_stock_history`
  ADD CONSTRAINT `fk_psh_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `product_tags`
--
ALTER TABLE `product_tags`
  ADD CONSTRAINT `product_tags_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `returns_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
