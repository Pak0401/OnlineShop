-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2024 at 05:32 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `product_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `new_pdata`
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
-- Dumping data for table `new_pdata`
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
