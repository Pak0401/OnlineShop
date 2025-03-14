-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2025-03-14 14:42:21
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
-- 資料庫: `storage`
--

-- --------------------------------------------------------

--
-- 資料表結構 `inventorydata`
--

DROP TABLE IF EXISTS `inventorydata`;
CREATE TABLE IF NOT EXISTS `inventorydata` (
  `PID` int(11) NOT NULL,
  `PName` varchar(255) NOT NULL,
  `Variant` varchar(255) NOT NULL,
  `Quantity` int(11) DEFAULT '100',
  PRIMARY KEY (`PID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `inventorydata`
--

INSERT INTO `inventorydata` (`PID`, `PName`, `Variant`, `Quantity`) VALUES
(1, 'DogP01', '5kg', 100),
(2, 'DogP01', '10kg', 100),
(3, 'CatP01', '5kg', 100),
(4, 'CatP01', '10kg', 100),
(5, 'CleanP01', '500ml', 100),
(6, 'CleanP01', '950ml', 100),
(7, 'CatP02', 'Tofu Cat Litter', 100),
(8, 'CatP03', 'GreenTea Tofu Cat Litter', 100),
(9, 'CatP04', 'Cat Toy', 100),
(10, 'DogP02', '50 sheets', 100),
(11, 'DogP03', '100 sheets', 100),
(12, 'DogP04', 'Small', 100),
(13, 'DogP04', 'Medium', 100),
(14, 'DogP04', 'Large', 100),
(15, 'CleanP02', '500ml', 100),
(16, 'CleanP02', '950ml', 100),
(17, 'CleanP03', '500ml', 100),
(18, 'CleanP03', '950ml', 100),
(19, 'CleanP04', '500ml', 100),
(20, 'CleanP04', '950ml', 100);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
