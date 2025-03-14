-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2025-03-14 14:44:55
-- 伺服器版本： 5.7.36
-- PHP 版本： 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `orderdata`
--

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` varchar(100) NOT NULL,
  `UID` int(255) NOT NULL,
  `items_text` text,
  `total_price` decimal(10,0) NOT NULL,
  `status` enum('未付款','已付款','取消','處理中','已完成') NOT NULL,
  `shipment_status` enum('未發貨','已發貨') DEFAULT '未發貨',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`) USING BTREE,
  KEY `UID` (`UID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`order_id`, `UID`, `items_text`, `total_price`, `status`, `shipment_status`, `created_at`, `updated_at`) VALUES
('ORD17419625178374', 1, '3 - CatP01 * 1', '300', '已付款', '已發貨', '2025-03-14 14:28:37', '2025-03-14 14:29:22');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
