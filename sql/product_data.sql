-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2025-03-14 14:42:28
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
-- 資料庫: `product_data`
--

-- --------------------------------------------------------

--
-- 資料表結構 `featured_pdata`
--

DROP TABLE IF EXISTS `featured_pdata`;
CREATE TABLE IF NOT EXISTS `featured_pdata` (
  `FPID` int(255) NOT NULL AUTO_INCREMENT,
  `FPName` varchar(255) NOT NULL,
  `Price` int(255) NOT NULL,
  `GID` int(255) NOT NULL,
  PRIMARY KEY (`FPID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `featured_pdata`
--

INSERT INTO `featured_pdata` (`FPID`, `FPName`, `Price`, `GID`) VALUES
(1, 'DogP01', 300, 1),
(2, 'DogP01', 500, 1),
(3, 'CatP01', 300, 2),
(4, 'CatP01', 500, 2),
(5, 'CatP04', 90, 6),
(6, 'DogP04', 60, 9),
(7, 'DogP04', 75, 9),
(8, 'DogP04', 85, 9);

-- --------------------------------------------------------

--
-- 資料表結構 `new_pdata`
--

DROP TABLE IF EXISTS `new_pdata`;
CREATE TABLE IF NOT EXISTS `new_pdata` (
  `NPID` int(255) NOT NULL AUTO_INCREMENT,
  `PName` varchar(255) NOT NULL,
  `Price` int(255) NOT NULL,
  `PID` int(255) NOT NULL,
  `GID` int(255) NOT NULL,
  PRIMARY KEY (`NPID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `new_pdata`
--

INSERT INTO `new_pdata` (`NPID`, `PName`, `Price`, `PID`, `GID`) VALUES
(1, 'CleanP01', 200, 5, 3),
(2, 'CleanP01', 250, 6, 3),
(3, 'CleanP02', 200, 15, 10),
(4, 'CleanP02', 250, 16, 10),
(5, 'CleanP03', 200, 17, 11),
(6, 'CleanP03', 250, 18, 11),
(7, 'CleanP04', 200, 19, 12),
(8, 'CleanP04', 250, 20, 12);

-- --------------------------------------------------------

--
-- 資料表結構 `productdata`
--

DROP TABLE IF EXISTS `productdata`;
CREATE TABLE IF NOT EXISTS `productdata` (
  `PID` int(255) NOT NULL AUTO_INCREMENT,
  `PName` varchar(255) NOT NULL,
  `Price` int(255) NOT NULL,
  `Variant` varchar(255) DEFAULT NULL,
  `GID` int(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `productdata`
--

INSERT INTO `productdata` (`PID`, `PName`, `Price`, `Variant`, `GID`, `Description`) VALUES
(1, 'DogP01', 300, '5kg', 1, 'Dog Food [Suitable for All Dogs]\r\nProtein dry food + carrot meat pellets series,\r\ncontaining a single formula of mutton and pumpkin'),
(2, 'DogP01', 500, '10kg', 1, 'Dog Food [Suitable for All Dogs]\r\nProtein dry food + carrot meat pellets series,\r\ncontaining a single formula of mutton and pumpkin'),
(3, 'CatP01', 300, '5kg', 2, ''),
(4, 'CatP01', 500, '10kg', 2, ''),
(5, 'CleanP01', 200, '500ml', 3, ''),
(6, 'CleanP01', 250, '950ml', 3, ''),
(7, 'CatP02', 300, 'Tofu Cat Litter', 4, 'Tofu Cat Litter'),
(8, 'CatP03', 300, 'GreenTea Tofu Cat Litter', 5, 'GreenTea Tofu Cat Litter'),
(9, 'CatP04', 90, 'Cat Toy', 6, 'Cat Toy'),
(10, 'DogP02', 375, '50 sheets', 7, 'Dog Sheet'),
(11, 'DogP03', 700, '100 sheets', 8, 'Dog Sheet'),
(12, 'DogP04', 60, 'Samll', 9, 'Tree Wood Chew\r\nFor Dog'),
(13, 'DogP04', 75, 'Medium', 9, 'Tree Wood Chew\r\nFor Dog'),
(14, 'DogP04', 85, 'Large', 9, 'Tree Wood Chew\r\nFor Dog'),
(15, 'CleanP02', 200, '500ml', 10, ''),
(16, 'CleanP02', 250, '950ml', 10, ''),
(17, 'CleanP03', 200, '500ml', 11, ''),
(18, 'CleanP03', 250, '950ml', 11, ''),
(19, 'CleanP04', 200, '500ml', 12, ''),
(20, 'CleanP04', 250, '950ml', 12, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
